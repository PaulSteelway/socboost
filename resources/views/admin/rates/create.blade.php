@extends('admin/layouts.app')
@section('title')
    {{ __('Create tariff plan') }}
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.rates.index')}}">{{ __('Tariff plans') }}</a></li>
    <li> {{ __('Create tariff plan') }}</li>
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
                    <h1 class="custom-font">{{ __('Create tariff plan') }}</h1>
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

                    <form class="form-horizontal" method="POST" action="{{ route('admin.rates.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">{{ __('Tariff plan name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required
                                       autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="currency">{{ __('Currency') }}</label>
                            <div class="col-md-4">
                                <select id="currency" name="currency_id" class="form-control">
                                    @foreach(getCurrencies() as $currency)
                                        <option value="{{ $currency['id'] }}">{{ $currency['code'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">{{ __('Min. investment') }}</label>
                            <div class="col-md-4">
                                <input id="textinput" name="min" type="number" step="any" placeholder="{{ old('min') }}" class="form-control input-md">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">{{ __('Max. investment') }}</label>
                            <div class="col-md-4">
                                <input id="textinput" name="max" type="number" step="any" placeholder="{{ old('max') }}" class="form-control input-md">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">{{ __('Daily') }}</label>
                            <div class="col-md-4">
                                <input id="textinput" name="daily" type="number" step="any" placeholder="{{ old('daily') }}" class="form-control input-md">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">{{ __('Overall') }}</label>
                            <div class="col-md-4">
                                <input id="textinput" name="overall" type="number" step="any" placeholder="{{ old('overall') }}" class="form-control input-md" value="0">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">{{ __('Duration') }}</label>
                            <div class="col-md-4">
                                <input id="textinput" name="duration" type="number" placeholder="{{ old('duration') }}" class="form-control input-md" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">{{ __('Payout') }}</label>
                            <div class="col-md-4">
                                <input id="textinput" name="payout" type="number" step="any"
                                       placeholder="{{ old('payout') }}" class="form-control input-md" value="100">
                            </div>
                        </div>

                        <!-- Multiple Checkboxes -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="checkboxes"></label>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label for="checkboxes-0">
                                        <input type="checkbox" value="1" name="reinvest" id="checkboxes-0">
                                        {{ __('Compounding') }}
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label for="checkboxes-1">
                                        <input type="checkbox" value="1" name="autoclose" id="checkboxes-1" checked>
                                        {{ __('Auto close') }}
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label for="checkboxes-2">
                                        <input type="checkbox" value="1" name="active" id="checkboxes-2" checked>
                                        {{ __('Active') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create plan') }}
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