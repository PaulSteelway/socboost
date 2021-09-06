@extends('admin/layouts.app')
@section('title')
    {{ __('Edit referral level') }} {{ $referral->level }}
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.referral.index')}}">{{ __('Referral levels') }}</a></li>
    <li> {{ __('Edit referral level') }} {{ $referral->level }}</li>
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
                    <h1 class="custom-font">{{ __('Edit referral level') }}</h1>
                    <ul class="controls">
                        <li>
                            <a role="button" tabindex="0" class="tile-fullscreen">
                                <i class="fa fa-expand"></i> {{ __('Fullscreen') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /tile header -->

                <!-- tile body -->
                <div class="tile-body">

                    <form class="form-horizontal" method="POST"
                          action="{{ route('admin.referral.update', $referral->id) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">{{ __('Level') }}</label>
                            <div class="col-md-6">
                                <input id="level" type="number" class="form-control" name="level"
                                       value="{{ $referral->level }}" required
                                       autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="percent" class="col-md-4 control-label">{{ __('Percent') }}</label>
                            <div class="col-md-6">
                                <input id="percent" type="number" step="any" class="form-control" name="percent"
                                       value="{{ $referral->percent }}" required>
                            </div>
                        </div>

                        <!-- Multiple Checkboxes -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="checkboxes">{{ __('Recharge on') }}</label>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label for="checkboxes-0">
                                        <input type="checkbox" name="on_load"
                                               id="checkboxes-0" {{ $referral->on_load ? 'checked' : '' }}>
                                        {{ __('balance recharge') }}
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label for="checkboxes-1">
                                        <input type="checkbox" name="on_profit"
                                               id="checkboxes-1" {{ $referral->on_profit ? ' checked' : '' }}>
                                        {{ __('earnings') }}
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label for="checkboxes-2">
                                        <input type="checkbox" name="on_task"
                                               id="checkboxes-2" {{ $referral->on_task ? ' checked' : '' }}>
                                        {{ __('task rewards') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                                <a href="{{ route('admin.referral.destroy', $referral->id) }}"
                                   class="btn btn-danger sure">{{ __('Destroy level') }}</a></a>
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
