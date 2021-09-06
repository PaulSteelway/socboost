@extends('layouts.customer')

@php
    $title = __('Ready account with followers') . ' - ' . __('site.site');
    $meta_title = __('site.title');
    $meta_desc = __('site.description');
    if (isset($category['meta_keywords']) && $category['meta_keywords']) {
      $meta_keywords = $category['meta_keywords'];
    }
    if (isset($category['title']) && $category['title']) {
      $title = $category['title'] . ' - ' . __('site.site');
    }
    if (isset($category['meta_title']) && $category['meta_title']) {
      $meta_title = $category['meta_title'] . ' - ' . __('site.site');
    }
    if (isset($category['meta_description']) && $category['meta_description']) {
      $meta_desc = $category['meta_description'];
    }

    if(app()->getLocale() == 'ru') {
      if (isset($category['meta_keywords_ru']) && $category['meta_keywords_ru']) {
        $meta_keywords = $category['meta_keywords_ru'];
      }
      if (isset($category['title_ru']) && $category['title_ru']) {
        $title = $category['title_ru'] . ' - ' . __('site.site');
      }
      if (isset($category['meta_title_ru']) && $category['meta_title_ru']) {
        $meta_title = $category['meta_title_ru'] . ' - ' . __('site.site');
      }
      if (isset($category['meta_description_ru']) && $category['meta_description_ru']) {
        $meta_desc = $category['meta_description_ru'];
      }
    }
@endphp

@section('title', $title)
@section('meta_title', $meta_title)
@section('description', $meta_desc)
@section('keywords', $meta_desc)


{{--@section('title', __('Ready accounts from') . ' - ' . __('site.site'))--}}

@section('content')
  @push('styles')
    <style>
        .bootbox-body {
            padding-top: 50px;
            padding-bottom: 25px;
        }

        [data-tooltip] {
            position: relative; /* Относительное позиционирование */
        }

        [data-tooltip]::after {
            content: attr(data-tooltip);
            position: absolute;
            width: 300px;
            left: 0;
            top: 0;
            background: #3989c9;
            color: #fff;
            padding: 0.5em;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            pointer-events: none;
            opacity: 0;
            transition: 1s;
        }

        [data-tooltip]:hover::after {
            opacity: 1;
            top: 2em;
        }

        .modal-dialog {
            top: 60px;
        }

        #accountData {
            display: block;
            width: 90%;
            border: 0;
        }

        .copy {
            cursor: pointer;
        }

        .bootbox-body {
            /*display: flex;*/
        }

        .account_info {
            display: flex;
        }

        .register-notice {
            position: absolute;
            bottom: 5px;
            text-align: center;
            background: #f57656;
            padding: 6px;
            border-radius: 10px;
        }
    </style>
  @endpush

    <main style="padding-top: 100px; min-height: 73vh">
        {{--ready acc2--}}
        <section class="ready-acc2 ready-acc1">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="ready-acc2__our-services">
                            <div class="ready-acc2__our-services-heading">
                                <div class="ready-acc2__our-services-title">{{__('Ready accounts from')}}:</div>
                                <button class="trigger-btn">
                                    <img src="https://socialbooster.me/img/likee.png" width="24" alt=""
                                         class="trigger-btn__img">
                                    <div class="trigger-btn__name">{!! app()->getLocale() == 'en'
                                                        ? $category->name_en
                                                        : $category->name_ru
                                            !!}</div>
                                </button>
                            </div>
                            <div class="service-page__accordion" id="service-page__accordion">
                                @foreach($categories as $cat)
                                    <div class="our-services__panel-container">
                                        <div class="our-services__panel-heading">
                                            <h4 class="our-services__panel-title">
                                                <a class="ready-acc2__accordion-link active"
                                                   href="/readyaccount/{{$cat['url']}}">
                                                    <div class="our-services__accordion-img">
                                                        <img src="{{ asset('/' . $cat['icon_img']) }}"
                                                             width="24" alt="">
                                                    </div>
                                                    <span class="our-services__accordion-link-name">{!! app()->getLocale() == 'en'
                                                        ? $cat->name_en
                                                        : $cat->name_ru
                                            !!}</span>
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="current-service">
                            <h1 class="current-service__title">
                                <img src="{{ asset('/' . $category->icon_img) }}" width="24" alt=""
                                     class="current-service__img">
                                {{--                                {!! app()->getLocale() == 'en'--}}
                                {{--                                                        ? $category->name_en--}}
                                {{--                                                        : $category->name_ru--}}
                                {{--                                            !!} ---}}
                                <span class="current-service__name">{!! app()->getLocale() == 'en'
                                                        ? $category->name_en
                                                        : $category->name_ru
                                            !!}</span>
                            </h1>
                            <div class="current-service__block">
                                <div class="buy-with__list">
                                    @foreach($products as $product)
                                        @if($product->productItems->isEmpty())
                                            @continue
                                        @endif
                                        <div class="buy-with__item">
                                            <div class="buy-with__item-img">
                                                <img src="{{ asset('/' . $product->icon_img) }}" alt="">
                                            </div>
                                            <div class="buy-with__item-name"> {!! app()->getLocale() == 'en'
                                                        ? $product->name_en
                                                        : $product->name_ru
                                            !!} <i class="fa fa-info-circle " id="topup-question" data-title="{!! app()->getLocale() == 'en'
                                                        ? $product->info
                                                        : $product->info_ru
                                            !!} "></i></div>
                                            <div class="buy-with__item-amount">{{$product->productItems->count()}} {{__('pc. left')}}</div>
                                            <div>
                                                <hr class="buy-with__item-hr">
                                                <div class="buy-with__item-price">{{__('for')}}
                                                    {{number_format(socialboosterPriceByAmount($product->price), 2, '.', '')}}
                                                    {{ app()->getLocale() == 'en' ? '$' : '₽' }}
                                                </div>
                                            </div>
                                            @if(!\Auth::guest())
                                                <div style="text-align: center">
                                                    <a href="#" onclick="purchaseAccount(event, {{$product->id}})"
                                                       class="buy-with-acc__btn">{{__('Buy')}}</a>
                                                </div>
                                            @else
                                                <div class="register-notice">{{__('Please register to purchase')}}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
      <script>
        function purchaseAccount(e, product_id) {
            e.preventDefault();

            $.ajax({
                url: '/checkoutAccountPurchase',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: {product_id: product_id},
                success: function (response) {
                    if (response.message.hasOwnProperty('widget')){
                        let payOrderData = response.message.widget
                        var payment = new UnitPay();
                        payment.createWidget(payOrderData);
                        payment.success(function (params) {
                            purchaseAccount(e, product_id);
                        });
                        payment.error(function (message, params) {
                            console.log(message);
                            console.log(params);
                        });
                        return false;
                    }
                    let account_info = `<div class="account_info">
                       <textarea id="accountData"  type="text" >${response.message}</textarea>
                       <i style="margin-left: 5px; margin-top: 5px;" onclick="copyToClipboard()" class="fa fa-clone copy"></i>
                    </div>`
                    if(Array.isArray(response.message)) {
                        account_info = '<div class="account_info"><table>'
                        response.message.forEach(function (value,key) {
                            account_info += '<tr><td><div>'+value[0]+':</div></td><td>'+'<div id="'+value[0]+'">'+value[1]
                                +'</div></td><td><i style="margin-left: 5px; margin-top: 5px;"' +
                                'onclick="copyToClipboard('+value[0]+')"class="fa fa-clone copy"></i></td></tr>'
                        })
                        account_info += '</table></div>'
                    }
                    bootbox.alert(`<p style="margin-bottom: 0; margin-top: 3px;">{{__('Your account info')}}:</p>`+account_info);
                },
                error: function (response) {
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.options.extendedTimeOut = 0;
                    toastr.warning('Your account info: ' + response.message);
                }
            });
        }

        function copyToClipboard(id_element) {
            var range = document.createRange();
            range.selectNode(id_element); //changed here
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand("copy");
            window.getSelection().removeAllRanges();
        }
      </script>
    @endpush
@endsection
