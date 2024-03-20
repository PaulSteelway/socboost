@extends('admin/layouts.app')
@section('title')
    {{ __('Referral levels') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Referral levels') }}</li>
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
                    <h1 class="custom-font">{{ __('Referral levels') }}</h1>
                    <ul class="controls">
                        <li>
                            <a role="button" href="{{ route('admin.referral.create') }}">
                                [<strong>{{ __('create new level') }}</strong>]
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
                    <table id="levels" class="table hover form-inline dt-bootstrap no-footer">
                        <thead>
                        <tr>
                            <th>{{ __('Level') }}</th>
                            <th>{{ __('Percent') }}</th>
                            <th>{{ __('On balance recharge') }}</th>
                            <th>{{ __('On profit recharge') }}</th>
                            <th>{{ __('On task recharge') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(getAffiliateLevels() as $level)
                            <tr>
                                <td>{{ $level['level'] }}</td>
                                <td>
                                    <strong>{{ $level['percent'] }}</strong>
                                </td>
                                <td>{{ $level['on_load'] ? __('yes') : __('no') }}</td>
                                <td>{{ $level['on_profit'] ? __('yes') : __('no') }}</td>
                                <td>{{ $level['on_task'] ? __('yes') : __('no') }}</td>
                                <td>
                                    <a href="{{ route('admin.referral.edit', $level['id']) }}" target="_blank"
                                       class="btn btn-primary">{{ __('edit') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @push('load-scripts')
                        <script>
                            $('#levels').DataTable();
                        </script>
                    @endpush
                </div>
                <!-- /tile body -->

            </section>
            <!-- /tile -->
            <!-- Debug Info -->
            
            </div>
            <!-- /Debug Info -->

        </div>
        <!-- /col -->
    </div>
    <!-- /row -->

@endsection
