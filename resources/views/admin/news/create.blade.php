@extends('admin.layouts.app')
@section('title')
    {{ __('Add news') }}
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.news.index')}}">{{ __('News list') }}</a></li>
    <li> {{ __('Add news') }}</li>
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
                    <h1 class="custom-font">{{ __('Add news') }}</h1>
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

                    <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ route('admin.news.store') }}">
                        {{ csrf_field() }}

                        @foreach(getLanguagesArray() as $lang)
                            <fieldset>
                                <legend>{{ __($lang['name']) }}</legend>
                                <div class="form-group">
                                    <label for="title" class="col-md-4 control-label">{{ __('Title') }}</label>
                                    <div class="col-md-6">
                                        <input id="title" type="text" class="form-control"
                                               name="title_{{ $lang['code'] }}"
                                               value="">
                                        <input type="hidden" name="lang_id_{{ $lang['code'] }}"
                                               value="{{ $lang['id'] }}">
                                    </div>
                                </div>

                                <!-- Textarea -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textarea">{{ __('Teaser') }}</label>
                                    <div class="col-md-6">
                                            <textarea class="form-control" id="textarea"
                                                      name="teaser_{{ $lang['code'] }}"> </textarea>
                                    </div>
                                </div>

                                <!-- Textarea -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textarea">{{ __('Content') }}</label>
                                    <div class="col-md-6">
                                            <textarea class="form-control" id="textarea"
                                                      name="text_{{ $lang['code'] }}"> </textarea>
                                    </div>
                                </div>
                            </fieldset>
                        @endforeach
                        <hr>
                        <!-- Add preview image -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="filebutton">{{ __('News image') }}</label>
                            <div class="col-md-4">
                                <input id="img" name="img" class="input-file" type="file" accept="image/*">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add news') }}
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