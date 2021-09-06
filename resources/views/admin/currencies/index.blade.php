@extends('admin/layouts.app')
@section('title')
    {{ __('Currencies') }}
@endsection
@section('breadcrumbs')
    <li>{{ __('Currencies') }}</li>
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
                    <h1 class="custom-font">{{ __('Currencies') }}</h1>
                    <ul class="controls">
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
                    <div class="tile-body">
                        <table id="currencies" class="table hover form-inline dt-bootstrap no-footer">
                            <thead>
                            <tr>
                                <th>{{ __('Currency name') }}</th>
                                <th>{{ __('Code') }}</th>
                                <th>{{ __('Precision') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(getCurrencies() as $currency)
                                <tr>
                                    <td>{{ $currency['name'] }}</td>
                                    <td style="font-weight: bold;">{{ $currency['code'] }}</td>
                                    <td style="font-weight: bold;">{{ $currency['precision'] }}</td>
                                    <td>
                                        <a type="button" class="btn btn-success btn-xs"
                                           href="{{ route('admin.currencies.edit', $currency['id']) }}">{{ __('edit') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @push('load-scripts')
                            <script>
                                $('#currencies').DataTable();
                            </script>
                        @endpush
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
