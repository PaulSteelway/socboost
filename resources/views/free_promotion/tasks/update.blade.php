@extends('layouts.free_promotion.auth-layout')

@section('title', "Все задания")

@section('content')
    <main style=" min-height: 73vh" class="card-container all-tasks">
        <h1>Все задания</h1>
        <div class="tasks__filters">
            <div class="tasks__filter">
                <label>Социальная сеть:</label>
                <select name="" id="">
                    <option value="">Все</option>
                    <option value="">Instagram</option>
                </select>
            </div>
            <div class="tasks__filter">
                <label>Социальная сеть:</label>
                <select name="" id="">
                    <option value="">Все</option>
                    <option value="">Instagram</option>
                </select>
            </div>
        </div>
        @include("free_promotion.components.tasks")
    </main>
@endsection
