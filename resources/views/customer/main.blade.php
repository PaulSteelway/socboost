@extends('layouts.customer-template')


@section('title', \App\Models\Setting::getValue('seo_title_' . app()->getLocale()) . ' - ' . __('site.site'))
@section('keywords', \App\Models\Setting::getValue('seo_keywords_' . app()->getLocale()))
@section('description', \App\Models\Setting::getValue('seo_description_' . app()->getLocale()))

@section('body_class', "homepage")

@section('content')

<main>
  <section class="our-services" id="our-services">

    <div class="container">
      <div class="row our-services__row">
        <div class="col-12 col-lg-7 our-services__text-container">
          <div class="our-services__text">
            <h2 class="our-services__title">
              {{ __('site.our_service') }}
            </h2>
            <p class="our-services__desc">
              {{__('Nowadays social networks are not only a place for online communication, but an excellent opportunity
              for self-expression and income generation. However, sometimes in order to get a significant result in the
              promotion, own forces are not enough. In this case, it is worth appealing to professionals')}}
            </p>
          </div>
        </div>

        <div class="col-12 col-lg-5">
          <div class="our-services__img">
            <img src="{{ asset('svg/promotion.svg') }}" alt="Promotion">
          </div>
        </div>
      </div>
    </div>

    @include('customer.main.our-service')
  </section>

  @include('customer.main.offer')
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="current-service">
          <h1 class="current-service__title"><span class="current-service__name">Категория</span>
          </h1>
          <noindex>
            <div class="current-service__block">
              <div class="current-service__packet">
                <h4 class="current-service__packet-title">Выберите нужный пакет:
                  <i class="fa fa-question-circle" aria-hidden="true"
                    data-title="Нажмите на название пакета и выберите один из доступных" id="topup-question"></i>
                </h4>
                <select name="packet" data-attribure="" class="current-service__packet-select packet-options"
                  style="-webkit-appearance: auto;">
                </select>
              </div>
            </div>
          </noindex>
        </div>
        <div class="current-service">
          <h1 class="current-service__title"><span class="current-service__name">Категория</span>
          </h1>
          <noindex>
            <div class="current-service__block">
              <div class="current-service__packet">
                <h4 class="current-service__packet-title">Выберите нужный пакет:
                  <i class="fa fa-question-circle" aria-hidden="true"
                    data-title="Нажмите на название пакета и выберите один из доступных" id="topup-question"></i>
                </h4>
                <select name="packet" data-attribure="" class="current-service__packet-select packet-options"
                  style="-webkit-appearance: auto;">
                </select>
              </div>
            </div>
          </noindex>
        </div>
        <div class="current-service">
          <h1 class="current-service__title"><span class="current-service__name">Категория</span>
          </h1>
          <noindex>
            <div class="current-service__block">
              <div class="current-service__packet">
                <h4 class="current-service__packet-title">Выберите нужный пакет:
                  <i class="fa fa-question-circle" aria-hidden="true"
                    data-title="Нажмите на название пакета и выберите один из доступных" id="topup-question"></i>
                </h4>
                <select name="packet" data-attribure="" class="current-service__packet-select packet-options"
                  style="-webkit-appearance: auto;">
                </select>
              </div>
            </div>
          </noindex>
        </div>
        
      </div>
    </div>
  </div>
  @include('customer.main.buy-ready')
  @include('customer.main.special-offers')
  @include('customer.main.subscribe')
  @include('customer.main.tg-bot')
  @include('customer.main.reviews')

  @push('scripts')
  @if(\Auth::check() && session()->exists(\Auth::id() . '_' . 'unitpay'))
  <script>
    var unitpay = {!! json_encode(session() -> pull(\Auth:: id(). '_'. 'unitpay'))!!};
  </script>
  <script>
    //     $(document).ready(function() {
    //     // Обработка изменения выбранной категории
    //     $('#category-select').change(function() {
    //         var categoryId = $(this).val();

    //         // Отправка запроса для получения данных по выбранной категории
    //         $.ajax({
    //             url: '/quickorder/' + categoryId,
    //             method: 'GET',
    //             dataType: 'json',
    //             success: function(response) {
    //                 if (response.status === 'success') {
    //                     // Если ответ success, показываем следующий блок и заполняем select
    //                     $('.first-block').hide();
    //                     $('.second-block').show();
    //                     $('.third-block').hide();
    //                     $('.order-block').hide();

    //                     // Очищаем селект от предыдущих опций
    //                     $('#category-select').empty().append($('<option>', {
    //                         value: '',
    //                         text: 'Select Category'
    //                     }));

    //                     // Заполняем селект данными из ответа AJAX
    //                     $.each(response.result, function(index, category) {
    //                         $('#category-select').append($('<option>', {
    //                             value: category.id,
    //                             text: category.name
    //                         }));
    //                     });

    //                     // Ваша логика для обработки категорий
    //                 } else if (response.status === 'order') {
    //                     // Если ответ order, показываем блок с полями заказа
    //                     $('.first-block').hide();
    //                     $('.second-block').hide();
    //                     $('.third-block').hide();
    //                     $('.order-block').show();

    //                     // Ваша логика для блока с полями заказа
    //                 } else {
    //                     // Если получен ответ, но не соответствующий success или order
    //                     // Ваша логика обработки других возможных статусов
    //                 }
    //             }
    //         });
    //     });
    // });

  </script>
  @endif
  @endpush

</main>

@endsection