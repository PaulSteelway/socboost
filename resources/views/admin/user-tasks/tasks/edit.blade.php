@extends('admin/layouts.app')
@section('title')
    {{ __('Edit task') }}
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.user-tasks.tasks.index')}}">{{ __('Tasks list') }}</a></li>
    <li> {{ __('Edit task') }}</li>
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
                    <h1 class="custom-font">{{ __('Edit task') }}</h1>
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

                    <form class="form-horizontal" method="POST" action="{{ route('admin.user-tasks.tasks.update', $task->id) }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="_method" value="put">
                        <input type="hidden" name="id" value="{{ $task->id }}">

                        <div class="form-group">
                            <label for="title" class="col-md-4 control-label">{{ __('Title') }}</label>
                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title" value="{{ $task->title }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="col-md-4 control-label">{{ __('Description') }}</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="description" id="description" required>{{ $task->description }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="reward_amount" class="col-md-4 control-label">{{ __('Reward amount') }}</label>
                            <div class="col-md-6">
                                <input id="reward_amount" type="number" step="any" class="form-control" name="reward_amount" value="{{ $task->reward_amount }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="reward_payment_system">{{ __('Reward payment system') }}</label>
                            <div class="col-md-4">
                                <select id="reward_payment_system" name="reward_payment_system" class="form-control">
                                    <option value="">{{ __('no selected') }}</option>
                                    @foreach(getPaymentSystems() as $paymentSystem)
                                        @foreach($paymentSystem['currencies'] as $currency)
                                            <option value="{{ $paymentSystem['id'] }}:{{ $currency['id'] }}"{{ $task->reward_payment_system_id == $paymentSystem['id'] && $task->reward_currency_id == $currency['id'] ? ' selected' : '' }}>{{ $paymentSystem['name'] }} [{{ $currency['code'] }}]</option>
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
                                    <option value="youtube"{{ $task->social_category == 'youtube' ? ' selected' : '' }}>Youtube</option>
                                    <option value="telegram"{{ $task->social_category == 'telegram' ? ' selected' : '' }}>Telegram</option>
                                    <option value="facebook"{{ $task->social_category == 'facebook' ? ' selected' : '' }}>Facebook</option>
                                    <option value="facebook"{{ $task->social_category == 'facebook' ? ' selected' : '' }}>Instagram</option>
                                    <option value="vilavi"{{ $task->social_category == 'vilavi' ? ' selected' : '' }}>Vilavi</option>
                                    <option value="blog"{{ $task->social_category == 'blog' ? ' selected' : '' }}>Blog</option>
                                    <option value="vk"{{ $task->social_category == 'vk' ? ' selected' : '' }}>Vkontakte</option>
                                    <option value="odnoklassniki"{{ $task->social_category == 'odnoklassniki' ? ' selected' : '' }}>Odnoklassniki</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="category">{{ __('Category') }}</label>
                            <div class="col-md-4">
                                <select id="category" name="category" class="form-control">
                                    <option value="">{{ __('not selected') }}</option>
                                    <option value="animation"{{ $task->category == 'animation' ? ' selected' : '' }}>{{ __('Animation') }}</option>
                                    <option value="business"{{ $task->category == 'business' ? ' selected' : '' }}>{{ __('Business') }}</option>
                                    <option value="gadgets"{{ $task->category == 'gadgets' ? ' selected' : '' }}>{{ __('Gadgets') }}</option>
                                    <option value="18plus"{{ $task->category == '18plus' ? ' selected' : '' }}>{{ __('18 plus') }}</option>
                                    <option value="games"{{ $task->category == 'games' ? ' selected' : '' }}>{{ __('Games') }}</option>
                                    <option value="beauty"{{ $task->category == 'beauty' ? ' selected' : '' }}>{{ __('Beauty') }}</option>
                                    <option value="lifehack"{{ $task->category == 'lifehack' ? ' selected' : '' }}>{{ __('Lifehack') }}</option>
                                    <option value="cartoons"{{ $task->category == 'cartoons' ? ' selected' : '' }}>{{ __('Cartoons') }}</option>
                                    <option value="news"{{ $task->category == 'news' ? ' selected' : '' }}>{{ __('News') }}</option>
                                    <option value="education"{{ $task->category == 'education' ? ' selected' : '' }}>{{ __('Education') }}</option>
                                    <option value="entertainment"{{ $task->category == 'entertainment' ? ' selected' : '' }}>{{ __('Entertainment') }}</option>
                                    <option value="sport"{{ $task->category == 'sport' ? ' selected' : '' }}>{{ __('Sport') }}</option>
                                    <option value="quotes"{{ $task->category == 'quotes' ? ' selected' : '' }}>{{ __('Quotes') }}</option>
                                    <option value="art"{{ $task->category == 'art' ? ' selected' : '' }}>{{ __('Art') }}</option>
                                    <option value="fashion"{{ $task->category == 'fashion' ? ' selected' : '' }}>{{ __('Fashion') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="deadline" class="col-md-4 control-label">{{ __('Task deadline') }}</label>
                            <div class="col-md-6">
                                <input id="deadline" type="text" class="form-control" name="deadline" value="{{ \Carbon\Carbon::parse($task->deadline)->format('m/d/Y h:i A') }}">
                            </div>
                        </div>

                        <hr>

                        <h3>{{ __('Reward coefficients') }}</h3>

                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                        <?php
                        /** @var TaskCoefficients $coefficients */
                        $coefficients = $task->coefficients()->orderBy('min_minutes');

                        if (0 === $coefficients->count()) {
                            echo '<strong>'.__('No registered coefficients').'</strong>';
                        } else {
                            $count = 1;

                            foreach ($coefficients->get() as $coefficient) {
                                if ($count > 1) {
                                    echo '<hr>';
                                }
                                ?>
                                {{ __('Minimum time of completing task (in minutes)') }}: <strong>{{ $coefficient->min_minutes }}</strong><br>
                                {{ __('Maximum time of completing task (in minutes)') }}: <strong>{{ $coefficient->max_minutes }}</strong><br>
                                {{ __('Reward coefficient') }}: <strong>{{ $coefficient->reward_coefficient }}</strong>
                                <?php
                                $count++;
                            }
                        }
                        ?>
                            </div>
                        </div>

                        <hr>

                        <h3>{{ __('Enter resources links') }}</h3>

                        @include('admin.user-tasks.tasks.scopes', [
                            'task' => $task,
                        ])

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update task') }}
                                </button>
                            </div>
                            <a href="{{ route('admin.user-tasks.tasks.destroy', $task->id) }}" class="btn btn-danger sure">{{ __('Delete task') }}</a>
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
