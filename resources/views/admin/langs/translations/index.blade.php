@extends('admin/layouts.app')
@section('title')
    {{ __('Template translations') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Template translations') }}</li>
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
                    <h1 class="custom-font">{{ __('Template translations') }}</h1>
                    <ul class="controls">
                        <li>
                            <a role="button"
                               href="{{ route('admin.tpl_texts.index',['category'=>'customer']) }}">{{ __('customer texts') }}
                            </a>
                        </li>
                        <li>
                            <a role="button"
                               href="{{ route('admin.tpl_texts.index',['category'=>'admin']) }}">{{ __('admin texts') }}
                            </a>
                        </li>
                        <li>
                            <a role="button"
                               href="{{ route('admin.tpl_texts.index',['category'=>'demo']) }}">{{ __('demo texts') }}
                            </a>
                        </li>
                        <li>
                            <a role="button" href="{{ route('admin.tpl_texts.create') }}"
                               style="font-weight: bold;">[{{ __('add new text') }}]
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
                    <div class="table-responsive">
                        <table class="table table-custom" id="translations-table">
                            <thead>
                            <tr>
                                <th>{{ __('Text') }}</th>
                                <th>{{ __('Language') }}</th>
                                <th>{{ __('Actions') }}</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($texts as $text)
                                <tr>
                                    <td>{{ $text->text }}</td>
                                    <td style="font-weight: bold;">{{ $text->lang->name }}</td>
                                    <td>
                                        <a type="button" class="btn btn-primary btn-xs" style="display:block;"
                                           href="{{ route('admin.tpl_texts.edit',  $text->id) }}">{{ __('edit') }}</a>
                                        <a type="button" class="btn btn-warning btn-xs"
                                           style="display: block; margin-top:5px;" href="#" onclick="
                                                var result = confirm('{{ __('Please confirm deletion') }}');
                                                if(result) {
                                                event.preventDefault();
                                                document.getElementById('delete-{{ $text->id }}').submit()
                                                }">{{ __('delete') }}</a>
                                        <form action="{{ route('admin.tpl_texts.destroy', $text->id) }}"
                                              method="POST"
                                              id="delete-{{ $text->id }}" style="display: none;">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
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
        var table = $('#translations-table').DataTable({
            "order": [[0, "desc"]],
        });

        $('#translations-table tbody').on('click', 'tr', function () {
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
