@extends('layouts.free_promotion.auth-layout')

@section('title', "Все задания")

@section('content')
    <section style=" min-height: 73vh" class="card-container tasks">
        <h1>Черный список</h1>

        @if(empty($tasks))
        <span>Ваш список пуст</span>
        @else
            @include("free_promotion.components.task-list", ['hide_actions' => true])
        @endif
    </section>

@endsection
