@if(!$tasks)
    <h1>Все задания</h1>
    <div class="tasks__filters">
        <div class="tasks__filter">
            {{Form::label('social_id', 'Социальная сеть:')}}
            <div class="select-wrapper arrow--icon">
                {!! Form::select('social_id',
                     array_merge(['0' => __('Все задания')], \App\Models\Category::where('free_promotion', 1)
                     ->whereNull('parent_id')
                     ->pluck('name_ru', 'id')->toArray()),
                     request()->social_id ?? null,
                     [ 'id' => 'social_id', 'onchange' => 'updateFilter(event, "social_id")'])
                 !!}
            </div>
        </div>
        <div class="tasks__filter">
            <label for="status_id">{{__('Status')}}:</label>
            <div class="select-wrapper arrow--icon">
                {!! Form::select('status_id',
                     ['' => '', 'inprocess' => 'Активный', 'complete' => 'Выполненный', 'stopped' => 'Остановленный',],
                     request()->status_id ?? null,
                     [ 'id' => 'status_id', 'onchange' => 'updateFilter(event, "status_id")'])
                 !!}
                {{--            <select id="status_id" onchange="updateFilter(event, 'status_id')">--}}
                {{--                <option value=""></option>--}}
                {{--                <option value="inprocess">{{__('Активный')}}</option>--}}
                {{--                <option value="complete">{{__('Выполненный')}}</option>--}}
                {{--                <option value="stopped">{{__('Остановленный')}}</option>--}}
                {{--            </select>--}}
            </div>
        </div>
    </div>
    <div class="tasks-table">
        @if($agent->isDesktop())

            <table>
                <thead>
                <tr>
                    <td>Социальная сеть</td>
                    <td>Услуга</td>
                    <td>Количество выполнений</td>
                    <td>Статус</td>
                </tr>
                </thead>
                <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>{{$task->category->parent->name_ru}}</td>
                        <td>{{$task->category->name_ru}}</td>
                        <td>{{$task->execution_qty}}</td>
                        @if($task->status == 0 AND $task->execution_qty > $task->executed_qty)
                            <td><span class="active-status">Активный</span></td>
                        @elseif($task->execution_qty <= $task->executed_qty)
                            <td><span class="complete-status">Выполненный</span></td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            @foreach($tasks as $task)

                <div class="task-card">
                    <div class="task-card__label">Социальная сеть:</div>
                    <div class="task-card__value">{{$task->category->parent->name_ru}}</div>
                    <div class="task-card__label">Услуга:</div>
                    <div class="task-card__value">{{$task->category->name_ru}}</div>
                    <div class="task-card__label">Количество выполнений:</div>
                    <div class="task-card__value">{{$task->execution_qty}}</div>
                    <div class="task-card__label">Статус</div>
                    @if($task->status == 0 AND $task->execution_qty > $task->executed_qty)
                        <div class="task-card__value"><span class="active-status">Активный</span></div>
                    @elseif($task->execution_qty <= $task->executed_qty)
                        <div class="task-card__value"><span class="active-complete">Выполненный</span></div>
                    @endif
                </div>
            @endforeach

        @endif

    </div>
@else
    <h1>У вас пока ещё нет задач</h1>
@endif

@push('scripts')
  <script>
    function updateFilter(e, filter_type) {
      var query = getQueryParams(window.location.search)
      query[filter_type] = e.target.value
      window.location.search = new URLSearchParams(query).toString()
    }


    function getQueryParams(url) {
      var qparams = {},
      parts = (url || '').split('?'),
      qparts, qpart,
      i = 0;

      if (parts.length <= 1) {
        return qparams;
      } else {
        qparts = parts[1].split('&');
        for (i in qparts) {

          qpart = qparts[i].split('=');
          qparams[decodeURIComponent(qpart[0])] =
          decodeURIComponent(qpart[1] || '');
        }
      }

      return qparams;
    };
  </script>
@endpush
