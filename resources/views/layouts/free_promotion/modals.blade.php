<!--modal-->

@push('styles')
  <style>
    #phone-change-form .iti--allow-dropdown {
      width: 100%;
    }

    .error {
      border: solid 2px red;
    }

    #error-msg, #modal-error-msg {
      color: red;
      font-weight: bolder;
      font-size: 10px;
    }

    .modal-add-link {
      font-size: 12px;
      font-weight: 600;
      color: rgba(53, 65, 77, 0.8);
      cursor: pointer;
    }

    @media (max-width: 767px) {
      #phoneCodeModal, #phoneChangeModal, #regOrderModal {
        margin-top: 40%;
      }

      .modal-add-link {
        margin-top: 19px;
      }
    }
  </style>
@endpush


<div class="modal" id="complainTask" tabindex="-1" style="overflow: hidden" data-backdrop="static" data-keyboard="false"
     role="dialog" aria-labelledby="complainTaskModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="register-form" method="POST" action="{{ route('freePromotion.add_to_blacklist') }}" name="signup" role="form"
                      class="modal-form">
                    {{ csrf_field() }}
                    <div id="registration-steps">
                        <div class="complaine-modal">
                            <div class="col-md-6 register-step__info "
                                 style="background-color: #f67555; opacity: 1">
                                <div class="modal_left_sidebar">
                                    <img src="/images/free_promotion/error.png" alt="">
                                    <h1>Пожаловаться на задание</h1>
                                    <p>Пожалуйста, сообщите причину, по которой задание должно быть заблокировано</p>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 register-step__element">
                                <h5 class="modal-title" id="regModalLabel">{{__('Причина')}}</h5>
                                <div class="register_field">
                                    <input type="hidden" name="task_id" >
                                    <div>
                                        <input type="checkbox" name="complain[not_avail]" class="modal-form__input">
                                    </div>
                                    <label for="complain[not_avail]" class="modal-form__label">Задание недоступно</label>
                                </div>
                                <div class="register_field">
                                    <div>
                                        <input type="checkbox" name="complain[fraud]" class="modal-form__input">
                                    </div>
                                    <label for="complain[fraud]" class="modal-form__label">Мошенничество</label>
                                </div>
                                <div class="register_field">
                                    <div>
                                        <input type="checkbox" name="complain[spam]" class="modal-form__input">
                                    </div>
                                    <label for="complain[spam]" class="modal-form__label">Рассылка спама</label>
                                </div>
                                <div class="register_field">
                                    <div>
                                        <input type="checkbox" name="complain[behavior]" class="modal-form__input">
                                    </div>
                                    <label for="complain[behavior]" class="modal-form__label">Оскорбительное поведение</label>
                                </div>
                                <div class="register_field--textarea">
                                    <label for="complain[comments]" class="modal-form__label">Коментарий</label>
                                    <textarea style="resize: none" name="complain[comments]" id="" cols="36" rows="5"></textarea>

                                </div>
                                <div class="register-errors" style="padding: 10px 10px 0 0;"></div>
                                <div class="complainTask__actions">
                                    <button class="btn">Пожаловаться</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<div class="modal" id="mobileMenu" tabindex="-1" style="overflow: hidden" data-backdrop="static" data-keyboard="false"
     role="dialog" aria-labelledby="mobileMenuModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div id="accordion">
                    <div class="menu_link">
                        <img src="{{asset('/images/free_promotion/task.svg')}}" width="14" alt=""
                             class="trigger-btn__img">
                        <a href="{{route('freePromotion.task.tasklist')}}" class="sidebar-nav__link menu_link--allitems ">
                            Все задания  ({{\App\Models\UserTasks\Tasks::where('user_id', !request('type') ? '!=' : '=', Auth::id())->get()->count()}})
                        </a>
                    </div>
                    @foreach( \App\Models\Category::where('free_promotion', 1)->whereNull('parent_id')->get() as $key => $category)
                        <div class="menu_link">
                            <div class="" id="headingOne">
                                <h5 class="mb-0">
                                    <img src="{{asset('/' . $category["icon_img"])}}" width="14" alt=""
                                         class="trigger-btn__img">
                                    <button class="btn btn-link collapsed " data-toggle="collapse" data-target="#collapse{{$key}}"
                                            aria-expanded="false" aria-controls="collapseTwo">
                                        {{$category->name_ru}}
                                        ({{$category->tasksCount(request('type'))}})
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse{{$key}}" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body menu_sublinks">
                                    @foreach($category->child_free_prom_categories as $service)
                                        <a href="{{route('freePromotion.task.tasklist', ['service_id' => $service->id, 'service_name' => $category->name_ru])}}"
                                           class="sidebar-nav__link sidebar-nav__sublink">{{$service->name_ru}} ({{$service->tasksCount(request('type'))}})</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="menu_link">
                        <img src="{{asset('/images/free_promotion/black.png')}}" width="24" alt=""
                             class="trigger-btn__img">
                        <a href="{{route('freePromotion.black_list')}}" class="sidebar-nav__link black-list ">Чёрный список</a>
                    </div>
                </div>
                <div class="mobileMenu__actions">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">Закрыть</button>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal" id="freeActionModal" tabindex="-1" role="dialog" style="overflow: hidden" data-backdrop="static"
     data-keyboard="false" aria-labelledby="regOrderModalLbl" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="freeActionModalBody"></div>
    </div>
</div>

<div style="display: none;">
    <div id="contentAttachingAccounts">
        <div class="modal-header">
            <h5 class="modal-title" id="freeActionModalLbl" style="text-align: center;">
                {{__('Прикрепление аккаунтов')}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div style="text-align: center; font-size: 14px; margin-top: -25px;">
                {{__('Чтобы можно было зарабатывать, выполняя задания, необходимо прикрепить свои аккаунты из социальных сетей')}}
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <a class="modal-form__link modal-form__link--inst" onclick="openInstViaLike()">
                    <img src="{{asset('img/socialite/instagram.jpeg')}}" width="50px">
                </a>
            </div>
        </div>
    </div>

    <div id="contentAttachingInstagramViaLike" style="text-align: center;">
        <img src="{{asset('img/socialite/instagram.jpeg')}}" width="50px">
        <div class="modal-header">
            <h5 class="modal-title" id="freeActionModalLbl" style="font-size: 27px;">
                {{__('Прикрепление аккаунта через лайк')}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="font-size: 14px; font-weight: 600; padding-top: 20px">
            <div>Укажите ваш @username</div>
            <form id="instViaLikeForm" method="POST" action="{{ route('freePromotion.auth.instagramViaLike') }}"
                  name="instViaLikeForm" role="form" class="modal-form">
                {{ csrf_field() }}
                <input type="text" name="username" class="modal-form__input" required placeholder="Пример: @username">
            </form>
            <div id="instViaLikeFormError"></div>
            <a class="fp_modal__actions--submit"
               onclick="openInstViaLikeLink('{{config('services.instagram.auth_like_url')}}')">
                {{__('Прикрепить')}}</a>
            <a class="fp_modal__actions--check" onclick="checkConnectViaLike()">{{__('Проверить')}}</a>
        </div>
    </div>
</div>
