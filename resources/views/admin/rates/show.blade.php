@extends('admin.layouts.app')
@section('title')
    {{ __('Tariff plan details') }} {{ $rate->name }}
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.rates.index')}}">{{ __('Tariff plans') }}</a></li>
    <li> {{ __('Tariff plan details') }}: {{ $rate->name }}</li>
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
                    <h1 class="custom-font">{{ __('Tariff plan details') }}</h1>
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
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th scope="row">{{ __('Tariff plan name') }}</th>
                                <td>{{ $rate->name }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Currency') }}</th>
                                <td><a href="{{ route('admin.currencies.edit', $rate->currency_id) }}"
                                       target="_blank">{{ $rate->currency->code }}</a></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Min. investment') }}</th>
                                <td style="font-weight: bold;">{{ $rate->min }}{{ $rate->currency->symbol }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Max. investment') }}</th>
                                <td style="font-weight: bold;">{{ $rate->max }}{{ $rate->currency->symbol }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Duration') }}</th>
                                <td>{{ $rate->duration }} {{ __('days') }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Daily earnings') }}</th>
                                <td>{{ $rate->daily }}%</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Overall') }}</th>
                                <td>{{ $rate->overall }}%</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Payout') }}</th>
                                <td>{{ $rate->payout }}%</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Created') }}</th>
                                <td>{{ $rate->created_at }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Last update') }}</th>
                                <td>{{ $rate->updated_at }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Active') }}</th>
                                <td>{{ $rate->active ? __('yes') : __('no') }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Compounding') }}</th>
                                <td>{{ $rate->reinvest ? __('yes') : __('no') }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Auto closing') }}</th>
                                <td>{{ $rate->autoclose ? __('yes') : __('no') }}</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <a href="{{ route('admin.rates.edit', $rate->id) }}"
                       class="btn btn-primary">{{ __('edit plan') }}</a>
                    <a href="{{ route('admin.rates.destroy', $rate->id) }}"
                       class="btn btn-danger sure">{{ __('destroy plan') }}</a>
                </div>
                <!-- /tile body -->

            </section>
            <!-- /tile -->

        </div>
        <!-- /col -->
    </div>
    <!-- /row -->

@endsection
