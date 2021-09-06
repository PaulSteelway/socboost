@extends('admin/layouts.app')
@section('title')
    {{ __('Email') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Email') }}</li>
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
                    <h1 class="custom-font">{{ __('Emails') }}</h1>
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
                          action="{{ route('admin.email.send-emails') }}">
                        {{ csrf_field() }}
                        <fieldset>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="email_country">{{ __('Countries') }}</label>
                                <div class="col-md-6">
                                    <select name="email_country" id="email_country" class="form-control input-md">
                                        <option value="1">Страны СНГ</option>
                                        <option value="2">Другие</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="textinput">{{ __('Email Title') }}</label>
                                <div class="col-md-6">
                                    <input id="textinput" name="email-title" type="tel"
                                           class="form-control input-md"
                                           value="">
                                    <span class="help-block"> </span>
                                </div>
                            </div>


                            <!-- Textarea -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="textarea">{{ __('Email Body') }}</label>
                                <div class="col-md-6">
                                            <textarea class="form-control" id="textarea" rows="10"
                                                      name="email_body"
                                                      required> </textarea>
                                </div>
                            </div>

                            <!-- Button -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="singlebutton"> </label>
                                <div class="col-md-4">
                                    <button id="singlebutton" class="btn btn-primary">{{ __('Send emails') }}</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                    @push('load-scripts')
                        <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
                        <script>
                            CKEDITOR.replace('email_body');
                        </script>
                    @endpush
                </div>
                <!-- /tile body -->
            </section>
            <!-- /tile -->
        </div>
        <!-- /col -->
    </div>
    <!-- /row -->
@endsection
