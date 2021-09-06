@extends(isFreePromotionSite() ? 'layouts.free_promotion.layout' : 'layouts.profile')

@section('title', __('Tickets') . ' - ' . __('site.site'))

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

    .ticket-action-btn {
      min-width: 100px;
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

    .ticket-action-btn:hover {
      background-color: #3d9ad8;
      color: #ffffff;
    }

    .ticket-btn-create {
      padding: 0 20px;
      min-width: 150px;
      float: right;
      margin: -20px 0 10px 0;
    }

    @media (max-width: 767px) {
      .ticket-action-btn {
        width: 100%;
      }

      .ticket-btn-create {
        margin: -50px 0 10px 0;
      }

      .history__td--left .history__td:first-of-type:before {
        content: 'ID:';
      }

      .history__td--left .history__td:last-of-type:before {
        content: 'Subject:';
      }

      .history__table--right .history__td:first-of-type:before {
        content: 'Status:';
      }

      .history__table--right .history__td:nth-of-type(2):before {
        content: '{{__('Last Update')}}:';
      }

      .history__table--right .history__td:nth-of-type(3):before {
        content: none;
      }
    }
  </style>
  @endpush

    <main style="padding-top: 100px; min-height: calc(100vh - 100px);">
        <section class="history">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="history__content">
                            <a type="button" class="ticket-action-btn ticket-btn-create"
                               href="{{route('profile.tickets.create')}}">+ {{__('Create new ticket')}}</a>
                            @if ($tickets->isEmpty())
                                <div class="list-cap" style="margin-top: 30px;">
                                    {{app()->getLocale() == 'en' ? 'Oops .. No tickets yet ...' : 'Упс.. Тикетов ещё нет...'}}
                                </div>
                            @else
                                <table class="history__table">
                                    <thead class="history__thead">
                                    <tr class="history__tr">
                                        <th class="history__th history__th--left">
                                            <table class="history__table history__table--left">
                                                <tr class="history__tr">
                                                    <th class="history__th">{{__('ID')}}</th>
                                                    <th class="history__th">{{__('Subject')}}</th>
                                                </tr>
                                            </table>
                                        </th>
                                        <th class="history__th history__th--right">
                                            <table class="history__table history__table--right">
                                                <tr class="history__tr">
                                                    <th class="history__th">{{__('Status')}}</th>
                                                    <th class="history__th">{{__('Last Update')}}</th>
                                                    <th class="history__th"></th>
                                                </tr>
                                            </table>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="history__tbody">
                                    @foreach($tickets as $ticket)
                                        <tr class="history__tr">
                                            <td class="history__td history__td--left">
                                                <table class="history__table history__table--left">
                                                    <tr class="history__tr">
                                                        <td class="history__td">{{ $ticket->id }}</td>
                                                        <td class="history__td">
                                                            {{ __(config('enumerations')['tickets']['subjects'][$ticket->subject]) }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td class="history__td history__td--right">
                                                <table class="history__table history__table--right">
                                                    <tr class="history__tr">
                                                        <td class="history__td">
                                                            {{ __(config('enumerations')['tickets']['statuses'][$ticket->status]) }}
                                                        </td>
                                                        <td class="history__td ">{{ $ticket->updated_at }}</td>
                                                        <td class="history__td ">
                                                            <a type="button" class="ticket-action-btn"
                                                               href="{{route('profile.tickets.show', $ticket->id)}}">{{__('Details')}}</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $tickets->onEachSide(1)->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
