<div class="new-task__action">
    <a href="{{route('freePromotion.task.create')}}">{{__('Новое задание')}}</a>
</div>

<div class="sidebar-nav">
    <div id="accordion">
        <div class="menu_link">
            <a href="{{route('freePromotion.task.tasklist', ['type' => request('type')])}}" class="sidebar-nav__link menu_link--allitems ">
                @php($userTasksId = \App\Models\UserTasks\UserTasks::select('task_id')->where('user_id', Auth::id())->whereIn('status', [2, 3])->get())

                <?php
                    $tasksCount = \App\Models\UserTasks\Tasks::where('user_id', !request('type') ? '!=' : '=', Auth::id())
                            ->whereNotIn('id', $userTasksId)
                            ->whereRaw('tasks.execution_qty > tasks.executed_qty')
                            ->whereRaw('tasks.execution_qty > (SELECT COUNT(ut.id) FROM user_tasks ut WHERE ut.task_id = tasks.id)')
                            ->count()
                ?>

                Все задания ({{ $tasksCount }})
            </a>
        </div>
        @foreach( \App\Models\Category::where('free_promotion', 1)->whereNull('parent_id')->get() as $key => $category)
            <div class="">
                <div class="" id="headingOne">
                    <h5 class="mb-0">
                        <img src="{{asset('/' . $category["icon_img"])}}" width="24" alt=""
                             class="trigger-btn__img">
                        <button class="btn btn-link collapsed menu_link" data-toggle="collapse" data-target="#collapse{{$key}}"
                                aria-expanded="false" aria-controls="collapseTwo">
                            {{$category->name_ru}}
                            ({{$category->tasksCount(request('type'))}})
                        </button>
                    </h5>
                </div>
                <div id="collapse{{$key}}" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body menu_sublinks">
                        @foreach($category->child_free_prom_categories as $service)
                            <a href="{{route('freePromotion.task.tasklist', [
                                         'service_id' => $service->id,
                                         'service_name' => $category->name_ru,
                                         'type' => request('type')
                                        ])}}"
                               class="sidebar-nav__link">
                                {{$service->name_ru}} ({{$service->tasksCount(request('type'))}})
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
        <div class="menu_link">
            <a href="{{route('freePromotion.black_list', ['type' => request('type')])}}" class="sidebar-nav__link black-list ">Чёрный список</a>
        </div>
    </div>
</div>
