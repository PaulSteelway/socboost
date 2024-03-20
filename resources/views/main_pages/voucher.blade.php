@extends('layouts.customer')

@section('title', __('Vouchers for our customers') . ' - ' . __('site.site'))

@section('content')
    <main style="padding-top: 100px; min-height: 73vh">
        <section class="voucher">
            <div class="container">
                <div class="row voucher__row">
                    <div class="col-12 col-md-6">
                        <div class="voucher__img">
                            <img src="images/present.png" alt="">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <h2 class="voucher__title">{{__('Vouchers for our customers')}}</h2>
                        <p class="voucher__text">{{__('Do you want to bring a gift for your close friend or love of your life who likes to get stuck in social networks and wants to promote his/her accounts? Are you thinking about what original gift can be made in the 21st century to avoid being boring, banal and surprise your loved one?')}}</p>
                        <p class="voucher__text">{{__('Gift voucher will be the best solution for absolutely any occasion! Give thousands of subscribers, tens of thousands of likes, millions of views!')}}</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="voucher-offers">
            <div class="container">
                <h2 class="voucher-offers__title">{{__('Suggestions for you')}}</h2>
                <div class="row">
                    @foreach($vouchers as $key => $voucher)
                        <div class="col-6 col-lg-3">
                            <form method="post" action="{{route('customer.voucher.purchase')}}">
                                <div class="voucher-offer">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="voucher_id" value="{{$key}}">
                                    <div class="voucher-offer__spent">{{__('You will get on balance')}}:</div>
                                    <div class="voucher-offer__discount">{{number_format(socialboosterPriceByAmount($voucher['price']), 2, '.', '')}} $</div>
                                    <div class="voucher-offer__spent">{{__('Voucher price')}}:</div>
                                    <div class="voucher-offer__spent-amount">{{__('for')}} {{number_format(socialboosterPriceByAmount($voucher['offer']), 2, '.', '')}} $</div>
                                    <div class="voucher-offer__btn-container">
                                        <button type="submit" class="voucher-offer__btn">{{__('Buy')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endforeach
                    <div class="col">
                        <h3 class="voucher-offers__subtitle">{{__('How can I get a voucher?')}}</h3>
                        <p class="voucher-offers__text">{{__('After successful payment, the voucher code will be sent to your email specified during registration. Verify that the email is valid.')}}</p>
                        <h3 class="voucher-offers__subtitle">{{__('How can I use the voucher?')}}</h3>
                        <p class="voucher-offers__text">{{__('In order to use the voucher activate it on the top-up page. The amount of the voucher will be instantly credited to your balance.' )}}</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
