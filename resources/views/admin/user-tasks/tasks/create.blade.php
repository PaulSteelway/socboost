@extends('admin/layouts.app')
@section('title')
    {{ __('Register new task') }}
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.user-tasks.tasks.index')}}">{{ __('Tasks list') }}</a></li>
    <li> {{ __('Register new task') }}</li>
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
                    <h1 class="custom-font">{{ __('Register new task') }}</h1>
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

                    <form class="form-horizontal" method="POST" action="{{ route('admin.user-tasks.tasks.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="title" class="col-md-4 control-label">{{ __('Title') }}</label>
                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="col-md-4 control-label">{{ __('Description') }}</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="description" id="description" required>{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="reward_amount" class="col-md-4 control-label">{{ __('Reward amount') }}</label>
                            <div class="col-md-6">
                                <input id="reward_amount" type="number" step="any" class="form-control" name="reward_amount" value="0">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="reward_payment_system">{{ __('Reward payment system') }}</label>
                            <div class="col-md-4">
                                <select id="reward_payment_system" name="reward_payment_system" class="form-control">
                                    <option value="">{{ __('no selected') }}</option>
                                    @foreach(getPaymentSystems() as $paymentSystem)
                                        @foreach($paymentSystem['currencies'] as $currency)
                                        <option value="{{ $paymentSystem['id'] }}:{{ $currency['id'] }}">{{ $paymentSystem['name'] }} [{{ $currency['code'] }}]</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="social_category">{{ __('Social category') }}</label>
                            <div class="col-md-4">
                                <select id="social_category" name="social_category" class="form-control">
                                    <option value="">{{ __('no selected') }}</option>
                                    <option value="youtube">Youtube</option>
                                    <option value="telegram">Telegram</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="facebook">Instagram</option>
                                    <option value="vilavi"}>Vilavi</option>
                                    <option value="blog">Blog</option>
                                    <option value="vk"{>Vkontakte</option>
                                    <option value="odnoklassniki">Odnoklassniki</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="category">{{ __('Category') }}</label>
                            <div class="col-md-4">
                                <select id="category" name="category" class="form-control">
                                    <option value="">{{ __('not selected') }}</option>
                                    <option value="animation">{{ __('Animation') }}</option>
                                    <option value="business">{{ __('Business') }}</option>
                                    <option value="gadgets">{{ __('Gadgets') }}</option>
                                    <option value="18plus">{{ __('18 plus') }}</option>
                                    <option value="games">{{ __('Games') }}</option>
                                    <option value="beauty">{{ __('Beauty') }}</option>
                                    <option value="lifehack">{{ __('Lifehack') }}</option>
                                    <option value="cartoons">{{ __('Cartoons') }}</option>
                                    <option value="news">{{ __('News') }}</option>
                                    <option value="education">{{ __('Education') }}</option>
                                    <option value="entertainment">{{ __('Entertainment') }}</option>
                                    <option value="sport">{{ __('Sport') }}</option>
                                    <option value="quotes">{{ __('Quotes') }}</option>
                                    <option value="art">{{ __('Art') }}</option>
                                    <option value="fashion">{{ __('Fashion') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="deadline" class="col-md-4 control-label">{{ __('Task deadline') }}</label>
                            <div class="col-md-6">
                                <input id="deadline" type="text" class="form-control" name="deadline" value="">
                            </div>
                        </div>

                        <hr>

                        <h3>{{ __('Reward coefficients') }}</h3>

                            <div class="task_coefficients">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{ __('Minimum time of completing task (in minutes)') }}</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="coefficients[min_minutes][]" value="" placeholder="0">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="deadline" class="col-md-4 control-label">{{ __('Maximum time of completing task (in minutes)') }}</label>
                                    <div class="col-md-6">
                                        <input id="deadline" type="text" class="form-control" name="coefficients[max_minutes][]" value="" placeholder="10">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="deadline" class="col-md-4 control-label">{{ __('Reward coefficient') }}</label>
                                    <div class="col-md-6">
                                        <input id="deadline" type="text" class="form-control" name="coefficients[reward_coefficient][]" value="" placeholder="1.2">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-6">
                                    <input type="button" class="btn btn-default" id="one_more_coefficient" value="{{ __('One more coefficient') }}">
                                </div>
                            </div>

                        <hr>

                        <h3>{{ __('Enter resources links') }}</h3>

                        @include('admin.user-tasks.tasks.scopes')

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register new task') }}
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

@push('load-scripts')
    <script>
        $(document).ready(function(){
            $('#deadline').datetimepicker();
        });

        $('#one_more_coefficient').click(function(){
            let block = $('.task_coefficients').last();
            let clone = block.clone();

            block.after(clone).after('<hr>');
        });
    </script>
@endpush