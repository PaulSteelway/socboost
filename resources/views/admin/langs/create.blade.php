@extends('admin.layouts.app')
@section('title')
    {{ __('Create language') }}
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.langs.index')}}">{{ __('Languages') }}</a></li>
    <li> {{ __('Create language') }}</li>
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
                    <h1 class="custom-font">{{ __('Create language') }}</h1>
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

                    <form class="form-horizontal" method="POST" action="{{ route('admin.langs.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">{{ __('Language name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required
                                       autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">{{ __('Code') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="code"
                                       value="{{ old('code') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">{{ __('Original name') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="original_name"
                                       value="{{ old('original_name') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create language') }}
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