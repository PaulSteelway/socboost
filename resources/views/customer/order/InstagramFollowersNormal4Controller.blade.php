@extends('layouts.customer')

@section('title', __("Instagram Followers") . ' - ' . __('site.site'))

@section('content')
    <div class="row">
        <div class="service-content col-md-8">
            @if (empty($packets))

            <form name="add_order" method="post" action="{{ route('add_order', ['service' => 'SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS']) }}">
                <input type="hidden" name="type" value="instagram-followers-normal4">
                <h1>{{ __("Instagram Followers") }}</h1>
                <br>
                <div class="row">
                    <div class="label-name col-md-4">{{ __("Price / 1:") }}</div>
                    <div class="col-md-8 priceOne"><span id="price_base"><strong>{{ socialboosterPrice('SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_PRICE') }}</strong></span> {{ session()->has('lang') && session('lang') == 'ru' ? '₽' : '$' }}</div>
                </div>
                <div class="row">
                    <div class="label-name col-md-4">{{ __("Country:") }}</div>
                    <div class="col-md-8 options">
                        <select class="form-control shop-options-s" name="country">
                            <option class="{{ socialboosterPrice('SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_ALL_COUNTRIES_PRICE') }}"
                                    value="SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_ALL_COUNTRIES" selected>
                                {{ __("All countries") }} (+{{ socialboosterPrice('SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_ALL_COUNTRIES_PRICE') }} {{ session()->has('lang') && session('lang') == 'ru' ? '₽' : '$' }})
                            </option>
                            <option class="{{ socialboosterPrice('SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_RUSSIA_PRICE') }}"
                                    value="SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_RUSSIA">
                                {{ __("Russia") }} (+{{ socialboosterPrice('SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_RUSSIA_PRICE') }} {{ session()->has('lang') && session('lang') == 'ru' ? '₽' : '$' }})
                            </option>
                            <option class="{{ socialboosterPrice('SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_UKRAINE_PRICE') }}"
                                    value="SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_UKRAINE">
                                {{ __("Ukraine") }} (+{{ socialboosterPrice('SERVICE_ADD_ORDER_INSTAGRAM_FOLLOWERS_UKRAINE_PRICE') }} {{ session()->has('lang') && session('lang') == 'ru' ? '₽' : '$' }})
                            </option>
                        </select>
                    </div>
                </div>
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

                <input type="hidden" name="count_min" value="200"/>
                <div class="message"></div>
            {{ csrf_field() }}</form>

            @else
                <h1>{{ __('Instagram Followers') }}</h1>
                <br>
                @include('customer.order.form_packets', ['packets' => $packets])
            @endif
        </div>

<div class="text-page col-md-12"><!-- VK: Лайки ВКонтакте -->
            <ul>
                <li>
                    <span class="name">{{ __("Details:") }}</span>
                    <strong>{{ __("Speed of fulfilment and details:") }}</strong>
                    * {{ __("Real followers") }} <br />
                    * {{ __("2K / Per day") }} <br />
                    * {{ __("Auto refill") }}<br />
                </li>
                <li>
                    <span class="name">{{ __("Limits:") }}</span>
                    <strong>Important:</strong>
                    * {{ __("Min order is 100 followers") }}<br />
                    * {{ __("Max order is 150k followers") }}<br />

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
