@extends('admin.layouts.app')
@section('title')
    {{ __('Edit tariff plan') }} {{ $rate->name }}
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.rates.index')}}">{{ __('Tariff plans') }}</a></li>
    <li> {{ __('Edit tariff plan') }}: {{ $rate->name }}</li>
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
                    <h1 class="custom-font">{{ __('Edit tariff plan') }}</h1>
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
                          action="{{ route('admin.rates.update', $rate->id) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">{{ __('Tariff plan name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name"
                                       value="{{ $rate->name }}" required
                                       autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="currency">{{ __('Currency') }}</label>
                            <div class="col-md-4">
                                <select id="currency" name="currency_id" class="form-control">
                                    @foreach(getCurrencies() as $currency)
                                        <option value="{{ $currency['id'] }}"{{ $rate->currency_id == $currency['id'] ? ' selected' : '' }}>{{ $currency['code'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">{{ __('Min. investment') }}</label>
                            <div class="col-md-4">
                                <input id="textinput" name="min" type="number" step="any" value="{{ $rate->min }}"
                                       class="form-control input-md">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">{{ __('Max. investment') }}</label>
                            <div class="col-md-4">
                                <input id="textinput" name="max" type="number" step="any" value="{{ $rate->max }}"
                                       class="form-control input-md">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">{{ __('Daily') }}</label>
                            <div class="col-md-4">
                                <input id="textinput" name="daily" type="number" step="any" value="{{ $rate->daily }}"
                                       class="form-control input-md">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">{{ __('Overall') }}</label>
                            <div class="col-md-4">
                                <input id="textinput" name="overall" type="number" step="any" value="{{ $rate->overall }}"
                                       class="form-control input-md">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">{{ __('Duration') }}</label>
                            <div class="col-md-4">
                                <input id="textinput" name="duration" type="number"
                                       value="{{ $rate->duration }}" class="form-control input-md">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">{{ __('Payout') }}</label>
                            <div class="col-md-4">
                                <input id="textinput" name="payout" type="number" step="any" value="{{ $rate->payout }}"
                                       class="form-control input-md">
                            </div>
                        </div>

                        <!-- Multiple Checkboxes -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="checkboxes"> </label>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label for="checkboxes-0">
                                        <input type="checkbox" name="reinvest" value="1"
                                               id="checkboxes-0"{{ $rate->reinvest ? ' checked' : '' }}>
                                        {{ __('Compounding') }}
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label for="checkboxes-1">
                                        <input type="checkbox" name="autoclose" value="1"
                                               id="checkboxes-1"{{ $rate->autoclose ? ' checked' : '' }}>
                                        {{ __('Auto close') }}
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label for="checkboxes-2">
                                        <input type="checkbox" name="active" value="1"
                                               id="checkboxes-2"{{ $rate->active ? ' checked' : '' }}>
                                        {{ __('Active') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
                <!-- /tile body -->

            </section>
            <!-- /tile -->

        </div>
        <!-- /col -->
    </div>
    <!-- /row -->


@endsection
