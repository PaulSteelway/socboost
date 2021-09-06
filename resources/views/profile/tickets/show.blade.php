@extends(isFreePromotionSite() ? 'layouts.free_promotion.layout' : 'layouts.customer')

@section('title', __('Status') . ' - ' . __('site.site'))

@section('content')

  @push('styles')
    <style>
    body {
      background-size: cover;
      background-image: url(../images/bg-history.png);
      background-color: rgba(242, 237, 237, 0.7);
    }

    .ticket-header {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      justify-content: space-between;
      font-size: 14px;
      font-weight: 700;
      color: #35414d;
      margin-bottom: 10px;
    }

    .ticket-message {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      font-size: 16px;
      font-weight: 500;
      color: #35414d;
    }

    .message-container {
      background: #ffffff;
      border-radius: 10px;
      -webkit-box-shadow: 0 2px 4px 0 rgba(231, 231, 231, 0.5);
      box-shadow: 0 2px 4px 0 rgba(231, 231, 231, 0.5);
      border: solid 1px #e8e8e8;
      padding: 15px 30px 5px 30px;
      margin-bottom: 10px !important;
    }

    .message-container:last-of-type {
      margin-bottom: 0;
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
    }

    .ticket-action-btn:hover {
      background-color: #3d9ad8;
      color: #ffffff;
    }

    .ticket-btn-edit {
      min-width: 150px;
      margin: 0;
    }

    .ticket__header {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-pack: justify;
      -ms-flex-pack: justify;
      justify-content: space-between;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      margin-bottom: 20px;
    }

    .ticket-form {
      margin-top: 30px;
    }

    .ticket-form__textarea {
      width: 100%;
      height: 200px;
      padding: 10px 20px;
      border-radius: 4px;
      border: solid 1px #e3e3e3;
      margin: 0;
      background-color: #ffffff;
      font-size: 14px;
      font-weight: 500;
      color: #35414d;
    }

    .profile-form__btns {
      text-align: center;
    }

    .profile-form__btn--cancel {
      min-width: 250px;
      border-radius: 25px;
      background-color: #D0D0D0;
      border: none;
      color: #ffffff;
      font-size: 16px;
      font-weight: 500;
      height: 50px;
      margin-right: 31px;
      -webkit-transition: .3s ease-in;
      transition: .3s ease-in;
      text-align: center;
      line-height: 3em;
      -webkit-appearance: button !important;
    }

    .profile-form__btn--cancel:hover {
      background-color: #C0C0C0;
      color: #ffffff;
    }

    @media (max-width: 767px) {
      .message-container {
        padding: 15px 35px 0 20px;
      }

      .ticket-action-btn {
        width: 100%;
      }

      .ticket-btn-edit {
        margin: -50px 0 10px 0;
      }

      .ticket__header {
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
      }

      .ticket-form {
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
      }

      .profile-form__btn--cancel {
        width: 100%;
      }
    }
  </style>
@endpush

    <main style="padding-top: 100px;">
        <section class="faq">
            <div class="container">
                <div class="row">
                    <div class="col">
{{--                        <div class="ticket__header" style="justify-content: flex-end">--}}
{{--                            @if ($ticket->subject != 3)--}}
{{--                                <a type="button" class="ticket-action-btn ticket-btn-edit"--}}
{{--                                   href="{{route('profile.tickets.edit', $ticket->id)}}">{{__('+ Add Message')}}</a>--}}
{{--                            @endif--}}
{{--                        </div>--}}
                        <div class="ticket__header">
                            <h2 class="faq__title">{{__('Ticket')}} {{__('ID')}} {{$ticket->id}} ({{__('Subject')}}: {{__(config('enumerations')['tickets']['subjects'][$ticket->subject])}})</h2>
                            <h2 class="faq__title">{{__('Status')}}: {{__(config('enumerations')['tickets']['statuses'][$ticket->status])}}</h2>
                        </div>
                        @foreach($messages as $message)
                            <div class="panel-group">
                                <div class="message-container" style="background-color: {{ $message->user_id == \Auth::id() ? '#ffffff' : '#faf8f8'}};">
                                    <h4>
                                        <span class="ticket-header">
                                            <span>{{ $message->user_id == \Auth::id() ? __('You') : __('Admin')}}</span>
                                            <span>{{$message->created_at}}</span>
                                        </span>
                                        <span class="ticket-message">{{$message->message}}</span>
                                    </h4>
                                </div>
                            </div>
                        @endforeach

                        @if ($ticket->status != 3)
                            <form method="POST" name="ticket_create" role="form" action="{{ route('profile.tickets.update', $ticket->id) }}">
                                {{ csrf_field() }}

                                <div class="ticket-form">
                                    <div class="profile-form__field">
                                        <label for="ticket-message" class="profile-form__label">{{__('Message')}}*</label>
                                        <textarea name="message" id="ticket-message"
                                                  class="ticket-form__textarea"></textarea>
                                    </div>
                                </div>

                                <div class="profile-form__btns">
                                    <button class="profile-form__btn profile-form__btn--save">{{__('Save')}}</button>
                                    <a type="button" class="profile-form__btn profile-form__btn--cancel" href="{{route('profile.tickets.index')}}">{{__('Cancel')}}</a>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
