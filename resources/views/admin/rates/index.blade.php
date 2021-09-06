@extends('admin/layouts.app')
@section('title')
    {{ __('Tariff plans') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Tariff plans') }}</li>
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
                    <h1 class="custom-font">{{ __('Tariff plans') }}</h1>
                    <ul class="controls">
                        <li>
                            <a role="button"
                               href="{{ route('admin.rates.create') }}">[<strong>{{ __('create new tariff plan') }}</strong>]</a>
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
                    <table id="plans" class="table hover form-inline dt-bootstrap no-footer">
                        <thead>
                        <tr>
                            <th>{{ __('Plan name') }}</th>
                            <th>{{ __('Min. investment') }}</th>
                            <th>{{ __('Max. investment') }}</th>
                            <th>{{ __('Daily') }}</th>
                            <th>{{ __('Active') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        @foreach(getTariffPlans() as $plan)
                            <tbody>
                            <td>{{ $plan['name'] }}</td>
                            <td style="font-weight: bold;">{{ $plan['min'] }}{{ $plan['currency']['symbol'] }}</td>
                            <td style="font-weight: bold;">{{ $plan['max'] }}{{ $plan['currency']['symbol'] }}</td>
                            <td>{{ $plan['daily'] }}%</td>
                            <td>
                                {!! $plan['active'] == 1
                                    ? '<strong style="color:green;">'.__('yes').'</strong>'
                                    : '<strong style="color:red;">'.__('no').'</strong>' !!}
                            </td>
                            <td>
                                <a href="{{ route('admin.rates.show', $plan['id']) }}" target="_blank"
                                   class="btn btn-primary">{{ __('show') }}</a>
                            </td>
                            </tbody>
                        @endforeach
                    </table>
                    @push('load-scripts')
                        <script>
                            $('#plans').DataTable();
                        </script>
                    @endpush
                </div>
                <!-- /tile body -->
            </section>
            <!-- /tile -->
        </div>
        <!-- /col -->
    </div>
    <!-- /row -->
@endsection
