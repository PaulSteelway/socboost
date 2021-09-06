@extends('admin/layouts.app')
@section('title')
    {{ __('Edit currency') }} {{ $currency->code }}
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.currencies.index')}}">{{ __('Currencies') }}</a></li>
    <li> {{ __('Edit currency') }}: {{ $currency->code }}</li>
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
                    <h1 class="custom-font">{{ __('Edit currency') }}</h1>
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
                          action="{{ route('admin.currencies.update', $currency->id) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">

                        <div class="form-group">
                            <label for="name"
                                   class="col-md-4 control-label">{{ __('Name for') }} {{$currency->code}}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name"
                                       value="{{ $currency->name }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="precision" class="col-md-4 control-label">{{ __('Precision') }}</label>
                            <div class="col-md-6">
                                <input id="precision" type="number" class="form-control" name="precision"
                                       value="{{ $currency->precision }}" required>
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
