<div class="task-components">
    @if(empty(count($tasks)))
        <div>{{__('У вас ещё нет созданных заданий.')}}</div>
    @endif
    
    @foreach($tasks as $task)
        <div class="task-component" id="{{$task->id}}">
            <div class="task-component__image"><img
                        src="{{ asset($task->category->parent->icon_img) }}" alt=""></div>
            <div class="task-component__title">{{$task->title}}</div>
            <div class="task-component__text">{{$task->description}}</div>
            <div class="task-component__actions">
                @if(empty(\Auth::user()->userSocialProfile->instagram_username))
                    <a class="component__actions--submit" onclick="openIdentityModal()">+ {{$task->reward_amount}}
                        баллов</a>
                @else
                    <a class="component__actions--submit taskExecutionBtn" data-link="{{$task->link}}" data-task="{{$task->id}}">+ {{$task->reward_amount}} баллов</a>
                @endif
                @if(!isset($hide_actions) || !$hide_actions)
                    <div class="task-component__subactions">
                        <div><a href="#" onclick="hideTask(event, '{{$task->id}}')" class="component__actions--remove">Скрыть</a>
                        </div>
                        <div>
                            <button data-toggle="modal"
                                    data-target="#complainTask"
                                    data-task-id="{{$task->id}}"
                                    class="component__actions--block-task"

                            >
                                Заблокировать
                            </button>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    @endforeach
</div>

@push('scripts')
  <script>
  function hideTask(e, taskId) {
    e.preventDefault()
    $.ajax({
      url: '/hide_task',
      data: {task_id: taskId},
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'POST',
      dataType: 'JSON',
      success: function (response) {
        toastr.info('Задания удаленно')
        $('#' + taskId).remove();
      },
      error: function (response) {
        if(response.status == 200){
          toastr.info('Задания удаленно')
          $('#' + taskId).remove();
        }else{
          console.log(response)
        }
      }
    });
  }
</script>
@endpush
