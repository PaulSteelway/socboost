@extends('admin/layouts.app')
@section('title')
    {{ __('Register new bot') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Register new bot') }}</li>
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.telegram.bots.index')}}">{{ __('Bots list') }}</a></li>
    <li> {{ __('Register new bot') }}</li>
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
                    <h1 class="custom-font">{{ __('Register new bot') }}</h1>
                    <ul class="controls">
                        {{--<li>--}}
                            {{--<a role="button" href="#">--}}
                                {{--[<strong>{{ __('link') }}</strong>]--}}
                            {{--</a>--}}
                        {{--</li>--}}
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

                    <form class="form-horizontal" method="POST" action="{{ route('admin.telegram.bots.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="token" class="col-md-4 control-label">{{ __('BOT token [provided by @BotFather]') }}</label>
                            <div class="col-md-6">
                                <input id="token" type="text" class="form-control" name="token" value="{{ old('token') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="keyword">{{ __('BOT functionality') }}</label>
                            <div class="col-md-4">
                                <select id="keyword" name="keyword" class="form-control">
                                    @foreach(\App\Models\Telegram\TelegramBots::getExistsKeywords() as $keyword)
                                        <option value="{{ $keyword }}">{{ __($keyword) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="certificate" class="col-md-4 control-label">{{ __('Address of self-signed SSL certificate file [optional]') }}</label>
                            <div class="col-md-6">
                                <input id="certificate" type="text" class="form-control" name="certificate" value="{{ old('certificate') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="max_connections" class="col-md-4 control-label">{{ __('Maximum connection at the same time') }}</label>
                            <div class="col-md-6">
                                <input id="max_connections" type="number" class="form-control" name="max_connections" value="40">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register new bot') }}
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