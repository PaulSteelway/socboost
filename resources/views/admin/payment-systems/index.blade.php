@extends('admin.layouts.app')
@section('title')
    {{ __('Payment systems') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Payment systems') }}</li>
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
                    <h1 class="custom-font">{{ __('Payment systems') }}</h1>
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
                    <table id="paymentSystems" class="table hover form-inline dt-bootstrap no-footer">
                        <thead>
                        <tr>
                            <th>{{ __('Payment system name') }}</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('External balances') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Models\PaymentSystem::all() as $ps)
                            <tr>
                                <td>{{ $ps['name'] }}</td>
                                <td style="font-weight: bold;">{{ $ps['code'] }}</td>
                                <td>{!! $ps['connected'] ? '<strong class="label bg-greensea" data-toggle="tooltip" data-placement="right" title="'.__('Customers can use this payment system').'">'.__('connection established').'</strong>' : '<strong class="label bg-red" data-toggle="tooltip" data-placement="right" title="'.__('Please, check the accesses and wait approx. 10 minutes for updating status.').'">'.__('connection failed').'</strong>' !!}</td>
                                <td style="font-weight: bold;">
                                    <?php
                                    $externalBalancesArray = json_decode($ps['external_balances'], true);
                                    ?>
                                    @if(is_array($externalBalancesArray) && count($externalBalancesArray))
                                        @foreach($externalBalancesArray as $code => $limit)
                                            {{ $limit }} {{ $code }}<br>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <a type="button" class="btn btn-primary btn-xs"
                                       href="{{ route('admin.payment-systems.edit', $ps['id']) }}">{{ __('edit') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @push('load-scripts')
                        <script>
                            $('#paymentSystems').DataTable();
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
