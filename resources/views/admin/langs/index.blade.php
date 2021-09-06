@extends('admin/layouts.app')
@section('title')
    {{ __('Languages') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Languages') }}</li>
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <!-- col -->
        <div class="col-md-12">

            <!-- tile -->
            <section class="tile">

                <!-- tile header -->
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">{{ __('Languages') }}</h1>
                    <ul class="controls">
                        <li>
                            <a role="button" href="{{ route('admin.langs.create') }}"
                               style="font-weight: bold;">[{{ __('create new language') }}]
                            </a>
                        </li>
                        <li>
                            <a role="button" class="tile-fullscreen">
                                <i class="fa fa-expand"></i> {{ __('Fullscreen') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /tile header -->

                <!-- tile body -->
                <div class="tile-body">

                    <ul class="list-group">
                        <table class="table table-custom" id="langs-table">
                            <thead>
                            <tr>
                                <th>{{ __('Language name') }}</th>
                                <th>{{ __('Code') }}</th>
                                <th>{{ __('Original name') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <style>
                                td.tdinput input {
                                    width: 100%;
                                }
                            </style>
                            @foreach(getLanguagesArray() as $language)
                                <tr>
                                    <td>{{ $language['name'] }}</td>
                                    <td>
                                        <strong>{{ $language['code'] }}</strong>
                                    </td>
                                    <td>{{ $language['original_name'] }}</td>
                                    <td>
                                        @if ($language['default'])
                                            <strong>{{ __('default language') }}</strong>
                                    @else
                                        <a type="button" class="btn btn-success btn-xs"
                                           href="{{ route('admin.langs.edit', $language['id']) }}">{{ __('edit') }}</a>
                                    @endif
                                    </td>
                                </tr>
                            </tfoot>
                            @endforeach
                        </table>
                    </ul>

                </div>
                <!-- /tile body -->

            </section>
            <!-- /tile -->

        </div>
        <!-- /col -->
    </div>
    <!-- /row -->

@endsection

@push('load-scripts')
    <script>
        //initialize basic datatable
        var table = $('#langs-table').DataTable();

        $('#langs-table tbody').on('click', 'tr', function () {
            if ($(this).hasClass('row_selected')) {
                $(this).removeClass('row_selected');
            }
            else {
                table.$('tr.row_selected').removeClass('row_selected');
                $(this).addClass('row_selected');
            }
        });
        //*initialize basic datatable
    </script>
@endpush
