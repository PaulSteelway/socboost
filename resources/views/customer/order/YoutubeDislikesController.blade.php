@extends('layouts.customer')

@section('title', __("YouTube Dislikes") . ' - ' . __('site.site'))

@section('content')
    <section class="add-order">
        <div class="row">
            <div class="service-content col-md-8">
                @if (empty($packets))

                <form name="add_order" method="post" action="{{ route('add_order', ['service' => 'SERVICE_ADD_ORDER_YOUTUBE_DISLIKES']) }}">
                    <input type="hidden" name="type" value="youtube-dislikes">
                    <h1>{{ __("YouTube Dislikes") }}</h1>
                    <br>
                    <div class="row">
                        <div class="label-name col-md-4">{{ __("Price / 1:") }}</div>
                        <div class="col-md-8 priceOne"><span id="price_base"><strong>{{ socialboosterPrice('SERVICE_ADD_ORDER_YOUTUBE_DISLIKES_PRICE') }}</strong></span> {{ session()->has('lang') && session('lang') == 'ru' ? '₽' : '$' }}</div>
                    </div>
                    {{--<div class="row">--}}
                        {{--<div class="label-name col-md-4">Страна:</div>--}}
                        {{--<div class="col-md-8 options">--}}
                            {{--<select class="form-control shop-options-s" name="options[country]">--}}
                                {{--<option class="0.00"--}}
                                        {{--value="0" selected>--}}
                                    {{--Все страны (0 {{ session()->has('lang') && session('lang') == 'ru' ? '₽' : '$' }})--}}
                                {{--</option>--}}
                                {{--<option class="0.07"--}}
                                        {{--value="1">--}}
                                    {{--Россия (+0,07 {{ session()->has('lang') && session('lang') == 'ru' ? '₽' : '$' }})--}}
                                {{--</option>--}}
                                {{--<option class="0.07"--}}
                                        {{--value="2">--}}
                                    {{--Украина (+0,07 {{ session()->has('lang') && session('lang') == 'ru' ? '₽' : '$' }})--}}
                                {{--</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row">--}}
                        {{--<div class="label-name col-md-4">Пол:</div>--}}
                        {{--<div class="col-md-8 options">--}}
                            {{--<select class="form-control shop-options-s" name="options[male]">--}}
                                {{--<option class="0.00"--}}
                                        {{--value="0" selected>--}}
                                    {{--Все (0 {{ session()->has('lang') && session('lang') == 'ru' ? '₽' : '$' }})--}}
                                {{--</option>--}}
                                {{--<option class="0.07"--}}
                                        {{--value="1">--}}
                                    {{--Мужской (+0,07 {{ session()->has('lang') && session('lang') == 'ru' ? '₽' : '$' }})--}}
                                {{--</option>--}}
                                {{--<option class="0.07"--}}
                                        {{--value="2">--}}
                                    {{--Женский (+0,07 {{ session()->has('lang') && session('lang') == 'ru' ? '₽' : '$' }})--}}
                                {{--</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row">--}}
                        {{--<div class="label-name col-md-4">Скорость:</div>--}}
                        {{--<div class="col-md-8 options">--}}
                            {{--<select class="form-control shop-options-s" name="options[speed]">--}}
                                {{--<option class="0.00"--}}
                                        {{--value="0" selected>--}}
                                    {{--Минимум (0 {{ session()->has('lang') && session('lang') == 'ru' ? '₽' : '$' }})--}}
                                {{--</option>--}}
                                {{--<option class="0.03"--}}
                                        {{--value="1">--}}
                                    {{--Медленно (+0,03 {{ session()->has('lang') && session('lang') == 'ru' ? '₽' : '$' }})--}}
                                {{--</option>--}}
                                {{--<option class="0.06"--}}
                                        {{--value="2">--}}
                                    {{--Стандарт (+0,06 {{ session()->has('lang') && session('lang') == 'ru' ? '₽' : '$' }})--}}
                                {{--</option>--}}
                                {{--<option class="0.09"--}}
                                        {{--value="3">--}}
                                    {{--Быстро (+0,09 {{ session()->has('lang') && session('lang') == 'ru' ? '₽' : '$' }})--}}
                                {{--</option>--}}
                                {{--<option class="0.12"--}}
                                        {{--value="4">--}}
                                    {{--VIP (+0,12 {{ session()->has('lang') && session('lang') == 'ru' ? '₽' : '$' }})--}}
                                {{--</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="row">
                        <div class="label-name col-md-4">{{ __("Quantity") }}:</div>
                        <div class="col-md-8"><input name="count" class="form-control" type="text" value="1" size="3"></div>
                    </div>
                    <div class="row">
                        <div class="label-name col-md-4">{{ __("Link") }}:</div>
                        <div class="col-md-8"><input name="link" class="form-control" type="text" required></div>
                    </div>
                    {{--<div class="row">--}}
                        {{--<div class="label-name col-md-4">Примечание: <span>(доступно только вам)</span></div>--}}
                        {{--<div class="col-md-8"><input name="note" class="form-control" type="text"></div>--}}
                    {{--</div>--}}
                    @include('customer.order.charge')
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-8"><input type="submit" class="btn-success" value="{{  __('Submit')  }}"></div>
                    </div>

                    <input type="hidden" name="count_min" value="50"/>
                    <div class="message"></div>
                {{ csrf_field() }}</form>

                @else
                    <h1>{{ __('YouTube Dislikes') }}</h1>
                    <br>
                    @include('customer.order.form_packets', ['packets' => $packets])
                @endif
            </div>



     <div class="text-page col-md-12"><!-- VK: Лайки ВКонтакте -->
            <ul>
                <li>
                    <span class="name">{{ __("Details:") }}</span>
                    <strong>{{ __("Speed of fulfilment and details:") }}</strong>
                                      * {{ __("Real dislikes") }}<br />
* {{ __("10k/ Dislikes per day") }}<br />
                 * {{ __("30 Days refill") }}<br />
                </li>
                <li>
                  <span class="name">{{ __("Limits:") }}</span>
                    <strong>{{ __("Important:") }}</strong>
                    * {{ __("Min order is") }} 10 <br />
                    * {{ __("Max order is") }} 600 000 {{ __("If u need more, just contact with our support") }}<br />

                </li>
            </ul></div>

        <div class="text-page col-md-12"></div>


        </div>
        <div class="border">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>

@endsection
