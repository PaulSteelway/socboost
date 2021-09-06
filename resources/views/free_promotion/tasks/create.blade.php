@extends('layouts.free_promotion.auth-layout')

@section('title', "Все задания")

@section('content')
    <section style=" min-height: 50vh" class="card-container tasks">
        <h1>Новое задание</h1>
        {{ Form::open(array('route' => 'freePromotion.task.create', 'id' => 'freePromotionTaskForm')) }}
            @include("free_promotion.tasks.task-fields")

            <div class="tasks__form-actions">
                <button class="button" type="reset">Отмена</button>
                <button class="button button-submit" type="button" onclick="createPromotionTask()">Создать задание</button>
            </div>
        {{ Form::close() }}

    </section>
    <section style=" min-height: 73vh" class="card-container tasks">
        @include("free_promotion.components.tasks")
    </section>

@endsection
