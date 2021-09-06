@push('styles')
  <style>
      body {
          background-size: cover;
          background-image: url(../images/bg-history.png);
          background-color: rgba(242, 237, 237, 0.7);
      }

      .history__th--left {
          width: 25%;
      }

      .history__th--right {
          width: 75%;
      }

      .history__th, .history__td {
          padding: 0 10px;
      }

      .history__table--left .history__th:nth-of-type(1), .history__table--left .history__td:nth-of-type(1) {
          width: 70%;
      }

      .history__table--left .history__th:nth-of-type(2), .history__table--left .history__td:nth-of-type(2) {
          width: 30%;
      }

      .history__table--right .history__th:nth-of-type(1), .history__table--right .history__td:nth-of-type(1),
      .history__table--right .history__th:nth-of-type(3), .history__table--right .history__td:nth-of-type(3) {
          width: 35%;
      }

      .history__table--right .history__th:nth-of-type(2), .history__table--right .history__td:nth-of-type(2),
      .history__table--right .history__th:nth-of-type(4), .history__table--right .history__td:nth-of-type(4) {
          width: 15%;
      }

      @media (max-width: 767px) {
          .history__tbody > .history__tr {
              padding: 14px 30px 14px 20px;
          }

          .history__table--left .history__th:nth-of-type(1), .history__table--left .history__td:nth-of-type(1),
          .history__table--left .history__th:nth-of-type(2), .history__table--left .history__td:nth-of-type(2),
          .history__table--right .history__th:nth-of-type(1), .history__table--right .history__td:nth-of-type(1),
          .history__table--right .history__th:nth-of-type(2), .history__table--right .history__td:nth-of-type(2),
          .history__table--right .history__th:nth-of-type(3), .history__table--right .history__td:nth-of-type(3),
          .history__table--right .history__th:nth-of-type(4), .history__table--right .history__td:nth-of-type(4) {
              width: 100%;
          }

          .history__table--left .history__td:nth-of-type(1):before {
              content: ' {{ __('Date') }}:';
          }

          .history__table--right .history__td:nth-of-type(1):before {
              content: ' {{ __('Service') }}:';
          }

          .history__table--right .history__td:nth-of-type(2):before {
              content: ' {{ __('Quantity and price') }}:';
          }

          .history__table--right .history__td:nth-of-type(3):before {
              content: ' {{ __('Link') }}:';
              display: block;
              color: #35414d;
              font-size: 12px;
          }

          .history__table--right .history__td:nth-of-type(3) {
              margin-bottom: 10px;
          }

          .history__table--right .history__td:nth-of-type(4):before {
              content: '{{__('Application status')}}:';
              display: block;
              color: #35414d;
              font-size: 12px;
          }
      }
  </style>
@endpush

{{-- nit:Daan Need refactor --}}
{{-- {{ dd(Auth::user()->newOrders->count()) }} --}}
@php($orders = \App\Models\Order::where('user_id', \Auth::user()->id)
            ->whereRaw('(jap_status IS NOT NULL OR product_id IS NOT NULL)')
            ->orderBy('created_at', 'desc')
            ->get())

<section class="history">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="history__content">
                    @if ($orders->isEmpty())
                        <div class="list-cap">
                            {{app()->getLocale() == 'en' ? 'Oops .. No orders yet ...' : 'Упс.. Заказов ещё нет...'}}
                        </div>
                    @else
                        <table class="history__table">
                            <thead class="history__thead">
                            <tr class="history__tr">
                                <th class="history__th history__th--left">
                                    <table class="history__table history__table--left">
                                        <tr class="history__tr">
                                            <th class="history__th">{{__('Date')}}</th>
                                            <th class="history__th">ID</th>
                                        </tr>
                                    </table>
                                </th>
                                <th class="history__th history__th--right">
                                    <table class="history__table history__table--right">
                                        <tr class="history__tr">
                                            <th class="history__th">{{__('Service')}}</th>
                                            <th class="history__th">{{__('Quantity and price')}}</th>
                                            <th class="history__th">{{__('Link')}}</th>
                                            <th class="history__th">{{__('Application status')}}</th>
                                        </tr>
                                    </table>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="history__tbody">
                            @foreach($orders as $order)

                                <tr class="history__tr">
                                    <td class="history__td history__td--left">
                                        <table class="history__table history__table--left">
                                            <tr class="history__tr">
                                                <td class="history__td">{{ $order->created_at }}</td>
                                                <td class="history__td">{{ empty($order->order_id) ? $order->id : $order->order_id }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="history__td history__td--right">
                                        <table class="history__table history__table--right">
                                            <tr class="history__tr">
                                                <td class="history__td">{{$order->name}}</td>
                                                <td class="history__td">{{ $order->quantity }}
                                                    / {{ socialboosterPriceByAmount($order->price) }}  {{ app()->getLocale() == 'en' ? '$' : '₽' }}</td>
                                                <td class="history__td" style="max-width:250px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                    @if(empty($order->link))
                                                        -
                                                    @else
                                                        <a href="{{$order->link}}" target="_blank">{{$order->link}}</a>
                                                    @endif
                                                </td>
                                                <td class="history__td">{{ __($order->jap_status) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
