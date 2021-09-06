<div class="mobile-nav-menu">
  <div class="mobile-nav-menu__action">
    <a href="{{route('freePromotion.task.create')}}" class="mobile-nav-menu__link">
      {{__('Новое задание')}}
    </a>

    <div style="display: flex">
      <div class="mobile-nav-menu__label">Задания:</div>

      @php
        $category = \App\Models\Category::where('free_promotion', 1)
          ->where('id', request('service_id'))
          ->first();
      @endphp

      <button data-toggle="modal" data-target="#mobileMenu" class="component__actions--block-task">
        @if($category)
          <img src="{{ asset('/' . $category->parent["icon_img"]) }}" width="24" class="trigger-btn__img">
        @else
          <img src="{{ asset('/images/free_promotion/task.svg') }}" width="24" class="trigger-btn__img">
        @endif

        @if(request('service_id'))
          {{ $category->parent->name_ru . ' (' . $category->tasksCount() . ') - ' . $category->name_ru}}
        @else
          Все задания
        @endif
      </button>

      <div class="mobile-nav-menu__list" style="display: none">
        @if($category)
          <img src="{{asset('/' . $category->parent["icon_img"])}}" width="24" class="trigger-btn__img">
        @else
          <img src="{{asset('/images/free_promotion/task.svg')}}" width="24" class="trigger-btn__img">
        @endif

        <a href="#menu" data-toggle="collapse" aria-expanded="false">
          @if(request('service_id'))
            {{ $category->parent->name_ru . ' (' . $category->tasksCount() . ') - ' . $category->name_ru}}
          @else
            Все задания
          @endif

          <ul class="collapse list-unstyled" id="menu">
            <li class="collapse__element" style="padding-left: 20px">
              @if (isset($category["icon_img"]))
                <img src="{{asset('/' . $category["icon_img"])}}" width="24" class="trigger-btn__img">
              @endif

              <a href="#homeSubmenu" class="sidebar-nav__link ">
                Все задания
                ()
              </a>
            </li>

            @php
              $all_categories = \App\Models\Category::where('free_promotion', 1)->whereNull('parent_id')->get();
            @endphp

            @foreach ($all_categories as $key => $category)
              <li class="collapse__element" style="padding-left: 20px">
                @if (isset($category["icon_img"]))
                  <img src="{{asset('/' . $category["icon_img"])}}" width="24" class="trigger-btn__img">
                @endif

                <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle sidebar-nav__link instagram_link">
                  {{$category->name_ru}}
                  ({{$category->tasksCount()}})
                </a>

                <ul class="collapse list-unstyled" id="homeSubmenu">
                  <li class="collapse__child-element">
                    <a href="{{route('freePromotion.task.tasklist', ['service_id' => $category->id, 'all' => true, 'service_name' => $category->name_ru])}}" class="sidebar-nav__link">
                      Все ({{$category->tasksCount()}})
                    </a>
                  </li>
                  @foreach($category->child_free_prom_categories as $service)
                    <li class="collapse__child-element">
                      <a href="{{route('freePromotion.task.tasklist', ['service_id' => $service->id, 'service_name' => $category->name_ru])}}" class="sidebar-nav__link">
                        {{$service->name_ru}}
                        ({{$service->tasksCount()}})
                      </a>
                    </li>
                  @endforeach

                </ul>

              </li>
            @endforeach

          </ul>
        </a>
      </div>
    </div>
  </div>
</div>
