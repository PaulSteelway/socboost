@extends('admin/layouts.app')

@section('title')
    {{ __('Edit user') }} {{ $user->name }}
@endsection

@section('breadcrumbs')
    <li><a href="{{route('admin.users.index')}}">{{ __('Users') }}</a></li>
    <li> @lang('Edit user'): {{ $user->name }}</li>
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
                    <h1 class="custom-font">{{ __('Edit user') }}</h1>
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

                    <form class="form-horizontal" method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">{{ __('User name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required
                                       autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lastname" class="col-md-4 control-label">{{ __('Last name') }}</label>
                            <div class="col-md-6">
                                <input id="lastname" type="text" class="form-control" name="lastname" value="{{ $user->lastname }}" required
                                       autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telegram" class="col-md-4 control-label">{{ __('Telegram') }}</label>
                            <div class="col-md-6">
                                <input id="telegram" type="text" class="form-control" name="telegram" value="{{ $user->telegram }}" required
                                       autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="col-md-4 control-label">{{ __('Phone') }}</label>
                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ $user->phone }}" required
                                       autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">{{ __('E-Mail Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email"
                                       value="{{ $user->email }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="api_key" class="col-md-4 control-label">{{ __('API Key') }}</label>
                            <div class="col-md-6">
                                <input id="api_key" type="text" class="form-control" name="api_key"
                                       value="{{ $user->api_key }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">{{ __('Is Premium Account') }}</label>
                            <div class="col-md-6">
                                <select id="is_premium" name="is_premium" class="form-control">
                                    <option value="1" {{ !empty($user->is_premium) ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                    <option value="0" {{ empty($user->is_premium) ? 'selected' : '' }}>{{ __('No') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">
                            </div>
                        </div>

                        @hasrole('root|admin')
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="selectmultiple">{{ __('Roles') }}</label>
                            <div class="col-md-4">
                                <select id="selectmultiple" name="roles[]" class="form-control" multiple="multiple">
                                    @foreach($roles as $role)
                                        @if ($user->hasRole($role['name']))
                                            <option value="{{ $role['name'] }}" selected>{{ $role['name'] }}</option>
                                        @else
                                            <option value="{{ $role['name'] }}">{{ $role['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endhasrole

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
