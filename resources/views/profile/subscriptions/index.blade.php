@extends('layouts.profile')

@section('title', __('Subscriptions') . ' - ' . __('site.site'))

@section('content')

  @push('styles')
    <style>
    body {
      background-size: cover;
      background-image: url(../images/bg-history.png);
      background-color: rgba(242, 237, 237, 0.7);
    }

    .pagination {
      padding-top: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: 500;
      color: #35414d;
    }

    .page-link {
      color: #35414d;
    }

    .page-item.active .page-link {
      z-index: 0;
      color: #35414d;
      background-color: #f3eeee;
      border-color: #dee2e6;
    }

    .subs-action-btn {
      min-width: 75px;
      border-radius: 25px;
      background-color: #54b5f5;
      border: none;
      color: #ffffff;
      font-size: 14px;
      font-weight: 500;
      height: 40px;
      -webkit-transition: .3s ease-in;
      transition: .3s ease-in;
      text-align: center;
      line-height: 2.8em;
      margin-top: 5px;
      -webkit-appearance: button !important;
    }

    .subs-action-btn:hover {
      background-color: #3d9ad8;
      color: #ffffff;
    }

    a.subs-close-btn {
      background: #f57656;
      color: #ffffff;
    }

    a.subs-close-btn:hover {
      background: #f55656;
      color: #ffffff;
    }

    @media (max-width: 767px) {
      .subs-action-btn {
        width: 100%;
      }

      .history__td--left .history__td:first-of-type:before {
        content: '{{__('ID')}}:';
      }

      .history__table--left .history__td:nth-of-type(2):before {
        content: '{{__('Type')}}:';
        display: inline-block;
        width: 38px;
        margin-right: 58px;
        margin-bottom: 11px;
      }

      .history__table--left .history__td:nth-of-type(3):before {
        content: '{{__('Status')}}:';
      }

      .history__table--right .history__td:first-of-type:before {
        content: '{{__('Service')}}:';
      }

      .history__table--right .history__td:nth-of-type(2):before {
        content: '{{__('Expiry')}}:';
      }

      .history__table--right .history__td:nth-of-type(3):before {
        content: none;
      }
    }
  </style>
  @endpush


    <main style="padding-top: 100px; min-height: 78vh;">
        <section class="history">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="history__content">
                            <table class="history__table">
                                <thead class="history__thead">
                                <tr class="history__tr">
                                    <th class="history__th history__th--left">
                                        <table class="history__table history__table--left">
                                            <tr class="history__tr">
                                                <th class="history__th">{{__('ID')}}</th>
                                                <th class="history__th">{{__('Type')}}</th>
                                                <th class="history__th">{{__('Status')}}</th>
                                            </tr>
                                        </table>
                                    </th>
                                    <th class="history__th history__th--right">
                                        <table class="history__table history__table--right">
                                            <tr class="history__tr">
                                                <th class="history__th">{{__('Service')}}</th>
                                                <th class="history__th">{{__('Expiry')}}</th>
                                                <th class="history__th"></th>
                                            </tr>
                                        </table>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="history__tbody">
                                @foreach($subscriptions as $subscription)
                                    <tr class="history__tr">
                                        <td class="history__td history__td--left">
                                            <table class="history__table history__table--left">
                                                <tr class="history__tr">
                                                    <td class="history__td">{{ $subscription->id }}</td>
                                                    <td class="history__td">{{ $subscription->type }}</td>
                                                    <td class="history__td">{{ $subscription->status }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="history__td history__td--right">
                                            <table class="history__table history__table--right">
                                                <tr class="history__tr">
                                                    <td class="history__td">
                                                        @if($subscription->type == 'PremiumAccount')
                                                            {{ __('Subscription for premium account') }}
                                                        @else
                                                            {{ app()->getLocale() == 'en' ? $subscription->packet->name_en : $subscription->packet->name_ru }}
                                                        @endif
                                                    </td>
                                                    <td class="history__td">{{ (new \Carbon\Carbon($subscription->date_at))->format('Y-m-d') }}</td>
                                                    <td class="history__td">
                                                        @if(in_array($subscription->status, ['new', 'active', 'Active']))
                                                            @if ($subscription->type == 'Order')
                                                                <a type="button" class="subs-action-btn"
                                                                   href="{{route('profile.subscriptions.show', $subscription->id)}}">{{__('Edit')}}</a>
                                                            @endif
                                                            <a type="button" data-id="{{$subscription->id}}"
                                                               class="subs-action-btn subs-close-btn">{{__('Close')}}</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $subscriptions->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
