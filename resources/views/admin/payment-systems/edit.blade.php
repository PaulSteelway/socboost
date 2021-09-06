@extends('admin.layouts.app')
@section('title')
    {{ __('Edit payment system') }} {{ $ps->name }}
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.payment-systems.index')}}">{{ __('Payment systems') }}</a></li>
    <li> {{ __('Edit payment system') }}: {{ $ps->name }}</li>
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
                    <h1 class="custom-font">{{ __('Edit payment system') }}</h1>
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

                    <form class="form-horizontal" method="POST"
                          action="{{ route('admin.payment-systems.update', $ps->id) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">{{ __('Payment system name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name"
                                       value="{{ $ps->name }}" required
                                       autofocus>
                            </div>
                        </div>


                        <div class="form-group">
                            <hr>
                            <strong style="margin-left:1%;">{{ __('Instant withdrawal limit') }}</strong>
                            <hr>
                            @foreach($currencies as $currency)
                                <label class="col-md-4 control-label">{{$currency->code}}</label>
                                <div class="col-md-6">
                                    <input id="instant_limit" type="text" class="form-control"
                                           name="instant_limit[{{$currency->code}}]"
                                           value="{{ $ps->instant_limit[$currency->code] }}" required>
                                    <span class="help-block">{{ __('0 - turn off') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            <hr>
                            <strong style="margin-left:1%;">{{ __('Minimum balance recharge amount') }}</strong>
                            <hr>
                            @foreach($currencies as $currency)
                                <label class="col-md-4 control-label">{{$currency->code}}</label>
                                <div class="col-md-6">
                                    <input id="minimum_topup" type="text" class="form-control"
                                           name="minimum_topup[{{$currency->code}}]"
                                           value="{{ $ps->minimum_topup[$currency->code] }}" required>
                                    <span class="help-block">{{ __('0 - any recharges') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            <hr>
                            <strong style="margin-left:1%;">{{ __('Minimum withdraw amount') }}</strong>
                            <hr>
                            @foreach($currencies as $currency)
                                <label class="col-md-4 control-label">{{$currency->code}}</label>
                                <div class="col-md-6">
                                    <input id="minimum_withdraw" type="text" class="form-control"
                                           name="minimum_withdraw[{{$currency->code}}]"
                                           value="{{ $ps->minimum_withdraw[$currency->code] }}" required>
                                    <span class="help-block">{{ __('0 - any withdraws') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <strong style="margin-left:1%;">{{ __('Connection links') }}</strong>
                    <hr>
                    <table class="table table-hover" style="margin-top:50px;">
                        <thead>
                        <tr>
                            <th>{{ __('Link type') }}</th>
                            <th>{{ __('URL') }}</th>
                            <th>{{ __('Request method') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(\Route::has($ps->code.'.status'))
                        <tr>
                            <td>{{ __('Status') }}</td>
                            <td>
                                <input type="text" value="{{ route($ps->code.'.status') }}" class="form-control" readonly>
                            </td>
                            <td>
                                <span class="label bg-info">POST</span>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td>{{ __('Success payment') }}</td>
                            <td>
                                <input type="text" value="{{ route('profile.topup.payment_message', ['result' => 'ok']) }}" class="form-control" readonly>
                            </td>
                            <td>
                                <span class="label bg-info">GET</span>
                            </td>
                        </tr>
                        <tr>
                            <td>{{ __('Failed payment') }}</td>
                            <td>
                                <input type="text" value="{{ route('profile.topup.payment_message', ['result' => 'error']) }}" class="form-control" readonly>
                            </td>
                            <td>
                                <span class="label bg-info">GET</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
                <!-- /tile body -->
            </section>
            <!-- /tile -->
        </div>
        <!-- /col -->
    </div>
    <!-- /row -->
@endsection
