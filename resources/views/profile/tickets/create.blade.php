@extends(isFreePromotionSite() ? 'layouts.free_promotion.layout' : 'layouts.customer')

@section('title', __('Tickets') . ' - ' . __('site.site'))

@section('content')

  @push('styles')
    <style>
    .my-ticket {
      background-image: url('../images/bg-profile.png');
      background-size: cover;
      padding-top: 100px;
      padding-bottom: 120px;
    }

    .ticket-form__title {
      font-size: 24px;
      font-weight: 600;
      color: #f57656;
      margin-bottom: 42px;
      max-width: 900px;
      margin-left: auto;
      margin-right: auto;
    }

    .ticket-form {
      max-width: 900px;
      margin: auto;
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

    .ticket-form__btns {
      text-align: center;
    }

    .profile-form__btn--cancel:hover {
      background-color: #C0C0C0;
      color: #ffffff;
    }

    @media (max-width: 767px) {
      .my-ticket {
        padding-top: 20px;
        padding-bottom: 30px;
      }

      .ticket-form__title {
        text-align: center;
        margin-bottom: 27px;
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
        <section class="my-ticket">
            <div class="container">
                <div class="row">
                    <div class="col">
                        @include('partials.inform')
                        <form method="POST" name="ticket_create" role="form" action="{{ route('profile.tickets.store') }}">
                            {{ csrf_field() }}

                            <h2 class="ticket-form__title">{{__('Create new ticket')}}</h2>

                            <div class="ticket-form">
                                <div class="profile-form__field">
                                    <label for="ticket-subject" class="profile-form__label">{{__('Subject')}}*</label>
                                    <select name="subject" id="ticket-subject" class="profile-form__input">
                                        @foreach(config('enumerations')['tickets']['subjects'] as $key => $subject)
                                            <option value="{{$key}}">{{ $subject == 'Order' && app()->getLocale() == 'ru' ? 'Заказ' : __($subject) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="profile-form__field">
                                    <label for="ticket-message" class="profile-form__label">{{__('Message')}}*</label>
                                    <textarea name="message" id="ticket-message" class="ticket-form__textarea"></textarea>
                                </div>
                            </div>

                            <div class="ticket-form__btns">
                                <button class="profile-form__btn profile-form__btn--save">{{__('Save')}}</button>
                                <a type="button" class="profile-form__btn profile-form__btn--cancel" href="{{route('profile.tickets.index')}}">{{__('Cancel')}}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
