<div class="row create-task__fields">

    <!-- Name Field -->
    <div class="form-group col-sm-12 col-lg-3 create-task__field--social">
        {!! Form::label('social_id', 'Социальная сеть') !!}
        {!! Form::select('social_id',
             \App\Models\Category::where('free_promotion', 1)->whereNull('parent_id')->pluck('name_ru', 'id')->toArray(),
             null,
             ['class' => 'form-control', 'id' => 'social_service'])
         !!}
    </div>

    <div class="form-group col-sm-12 col-md-3 col-lg-3 create-task__field--title">
        {!! Form::label('title', 'Название') !!}
        {!! Form::text('title', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-12 col-md-3 col-lg-3 create-task__field--description">
        {!! Form::label('description', 'Описание') !!}
        {!! Form::text('description', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-12 col-md-3 col-lg-3 create-task__field--link">
        {!! Form::label('link', __('Link')) !!}
        {!! Form::text('link', null, ['class' => 'form-control']) !!}
    </div>
{{--    <div class="offset-3"></div>--}}

{{--    <div class="offset-md-4"></div>--}}
    <div class="form-group col-sm-12 col-lg-3 create-task__field--category {{ $errors->has('category_id') ? 'has-error' : '' }}">
        {!! Form::label('category_id', 'Услуга') !!}
        {!! Form::select('category_id', [], null, ['class' => 'form-control', 'id' =>'service']) !!}
        @if($errors->has('category_id'))
            <p class="help-block">
                {{ $errors->first('category_id') }}
            </p>
        @endif
    </div>

    <div class="form-group col-sm-6 col-md-3 col-lg-3 create-task__field--reward">
        {!! Form::label('reward_amount', 'Оплата исполнителю') !!}
        {!! Form::text('reward_amount', null, ['class' => 'form-control', 'id' => 'taskRewardAmount']) !!}
        <span style="font-size: 10px">( Рекомендованная сумма -  3 балла)</span>
    </div>
    <div class="form-group col-sm-6 col-md-3 col-lg-3 create-task__field--qty">
        {!! Form::label('execution_qty', 'Количество выполнений') !!}
        {!! Form::text('execution_qty', null, ['class' => 'form-control', 'id' => 'taskExecutionQty']) !!}
    </div>

    <div class="offset-md-4"></div>
</div>
<div style="margin-bottom: 20px">
    <span id="totalRewardsContainer" style="display: none;">
        <a class="info-link">
            <b>{{__('Сумма списания')}} - <span id="totalRewardsSum"></span></b>
        </a><br>
    </span>
{{--    <div>--}}
{{--        <span>Итого</span>--}}
{{--        <span id="total_sum">0</span>--}}
{{--        <span>$</span>--}}
{{--    </div>--}}
    <span style="font-size: 12px">Комиссия биржи составляет - {{ config('tasks.commission') }}%, но не менее 1 балла за выполнение.</span>
</div>
{{--<div class="create-task__comment-info">--}}
{{--    <h2>Комментарии</h2>--}}
{{--    <p>Напишите комментарии, которые будут добавлять пользователи. Если вам нужны любые комментарии - просто оставьте эти--}}
{{--        поля пустыми.</p>--}}
{{--</div>--}}

{{--<div class="row create-task__field--comments" id="themplate_block">--}}
{{--    <div class="form-group col-sm-12 col-lg-6 ">--}}
{{--        {!! Form::label('comments[]', 'коментарий') !!}--}}
{{--        {!! Form::textarea('comments[]', null, ['class' => 'form-control', 'rows' => 4]) !!}--}}
{{--    </div>--}}
{{--</div>--}}
{{--<a href="#" onclick="addComment()" class="info-link">+ Добавить комментарий</a>--}}
{{--<template id="coment_themplate">--}}
{{--    <div class="form-group col-sm-12 col-lg-6">--}}
{{--        {!! Form::label('comments[]', 'коментарий') !!}--}}
{{--        {!! Form::textarea('comments[]', null, ['class' => 'form-control', 'rows' => 4]) !!}--}}
{{--    </div>--}}
{{--</template>--}}

@push('scripts')
  <script type="text/javascript">
    // $('#taskRewardAmount').keyup(function (){
    //     let amount = +$(this).val()
    //     let qty = +$('#taskExecutionQty').val()
    //     if(amount > 0 && qty > 0){
    //         $('#total_sum').text((amount * qty * 1.1).toFixed(2))
    //     }
    // })
    // $('#taskExecutionQty').keyup(function (){
    //     let qty = +$(this).val()
    //     let amount = +$('#taskRewardAmount').val()
    //     if(amount > 0 && qty > 0){
    //         $('#total_sum').text((amount * qty * 1.1).toFixed(2))
    //     }
    // })
    function addComment(){
      $('#themplate_block').append($('#coment_themplate').html())
    }

    var socialService = $("#social_service");
    get_services_by_category(socialService.val());
    socialService.change(function () {
      get_services_by_category($(this).val());
    });

    function get_services_by_category(service) {
      $.ajax({
        url: "{{ route('freePromotion.get_by_service') }}?service_id=" + service,
        method: 'GET',
        success: function (data) {
          $('#service').html(data.html);
        }
      });
    }
  </script>
@endpush
