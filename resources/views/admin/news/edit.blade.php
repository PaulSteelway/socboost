@extends('admin.layouts.app')
@section('title')
    {{ __('Edit news') }}
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.news.index')}}">{{ __('News list') }}</a></li>
    <li> {{ __('Edit news') }}</li>
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
                    <h1 class="custom-font">{{ __('Edit news') }}</h1>
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

                    <form class="form-horizontal" enctype="multipart/form-data" method="POST"
                          action="{{ route('admin.news.update', $news->id) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">

                        @foreach($newsData as $item)
                            <fieldset>
                                <legend>{{ __($item['lang_name']) }}</legend>
                                <div class="form-group">
                                    <label for="text" class="col-md-4 control-label">{{ __('News title') }}</label>
                                    <div class="col-md-6">
                                        <input id="title" type="text" class="form-control"
                                               name="title_{{ $item['lang_code'] }}"
                                               value="{{ $item['title'] }}">
                                        <input type="hidden" name="lang_id_{{ $item['lang_code'] }}"
                                               value="{{ $item['lang_id'] }}">
                                    </div>
                                </div>

                                <!-- Textarea -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textarea">{{ __('Teaser') }}</label>
                                    <div class="col-md-6">
                                            <textarea class="form-control" id="textarea"
                                                      name="teaser_{{ $item['lang_code'] }}">{{ $item['teaser'] }}</textarea>
                                    </div>
                                </div>

                                <!-- Textarea -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label"
                                           for="textarea">{{ __('News content') }}</label>
                                    <div class="col-md-6">
                                            <textarea class="form-control" id="summernote"
                                                      name="text_{{ $item['lang_code'] }}">{{ $item['text'] }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="text" class="col-md-4 control-label">{{ __('Publish date') }}</label>
                                    <div class="col-md-6">
                                        <input id="date" type="text" class="form-control"
                                               name="date_{{ $item['lang_code'] }}"
                                               value="{{ $item['created_at'] }}">
                                    </div>
                                </div>
                            </fieldset>
                        @endforeach

                        <hr>
                        <!-- Add preview image -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="filebutton">{{ __('News image') }}</label>
                            <div class="col-md-4">
                                <img src="{{ File::exists($img) ? $img : '' }}" alt="{{ __('no image') }}"
                                     class="img-thumbnail" width="50%"><br>
                                <input id="img" name="img" class="input-file" type="file" accept="image/*">
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
