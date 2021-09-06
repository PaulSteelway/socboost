@extends('admin/layouts.app')
@section('title')
    {{ __('Edit language') }} {{ $lang->name }}
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.langs.index')}}">{{ __('Languages') }}</a></li>
    <li> {{ __('Edit language') }}: {{ $lang->name }}</li>
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
                    <h1 class="custom-font">{{ __('Edit language') }}</h1>
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

                    <form class="form-horizontal" method="POST" action="{{ route('admin.langs.update', $lang->id) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">{{ __('Language name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $lang->name }}" required
                                       autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">{{ __('Code') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="code"
                                       value="{{ $lang->code }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">{{ __('Original name') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="original_name"
                                       value="{{ $lang->original_name }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">{{ __('default language') }}</label>
                            <div class="col-md-6">
                                <input type="checkbox" name="default" value="1" {{ $lang->default ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                                <a href="{{ route('admin.langs.destroy', $lang->id) }}" class="btn btn-danger sure">{{ __('Destroy language') }}</a>
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
