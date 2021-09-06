@extends(isFreePromotionSite() ? 'layouts.free_promotion.layout' : 'layouts.profile')

@section('title', __('Reset password') . ' - ' . __('site.site'))

@section('content')

  @push('styles')
    <style>
      .faq {
        padding-top: 5%;
        padding-bottom: 10%;
      }
    </style>
  @endpush

    <main style="padding-top: 100px;">
        <section class="faq">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
                                {{ csrf_field() }}

                                @if(empty($email))
                                    <input type="hidden" name="phone" value="{{ $token }}">

                                    <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                                        <label for="code" class="col-md-4 control-label">{{__('SMS Code')}}</label>
                                        <div class="col-md-6">
                                            <input id="code" type="text" class="form-control" name="code"
                                                   value="{{ $code ?? old('code') }}" required autofocus>

                                            @if ($errors->has('code'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('code') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email"
                                               class="col-md-4 control-label">Email</label>
                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control" name="email"
                                                   value="{{ $email ?? old('email') }}" required autofocus>

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password"
                                           class="col-md-4 control-label">{{ __("Password") }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password"
                                               required>

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <label for="password-confirm"
                                           class="col-md-4 control-label">{{ __("Re-enter password") }}</label>
                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control"
                                               name="password_confirmation" required>

                                        @if ($errors->has('password_confirmation'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __("Reset password") }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </main>
@endsection
