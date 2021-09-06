@extends('admin.layouts.app')
@section('title')
    {{ __('Add template translation') }}
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.tpl_texts.index')}}">{{ __('Template translations') }}</a></li>
    <li> {{ __('Add template translation') }}</li>
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
                    <h1 class="custom-font">{{ __('Add template translation') }}</h1>
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

                    <form class="form-horizontal" method="POST" action="{{ route('admin.tpl_texts.store') }}">
                        {{ csrf_field() }}

                        @foreach(getLanguagesArray() as $lang)
                            <div class="form-group">
                                <label for="text" class="col-md-4 control-label">{{ __($lang['name']) }}</label>
                                <div class="col-md-6">
                                    <input id="text" type="text" class="form-control" name="text_{{ $lang['code'] }}"
                                           value="">
                                    <input type="hidden" name="lang_id_{{ $lang['code'] }}" value="{{ $lang['id'] }}">
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="category">{{ __('Category') }}</label>
                            <div class="col-md-6">
                                <select id="category" name="category" class="form-control">
                                    <option value="customer">{{ __('Customer') }}</option>
                                    <option value="admin">{{ __('Admin') }}</option>
                                    <option value="demo">{{ __('Demo') }}</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create') }}
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