@extends('layouts.free_promotion.auth-layout')

@section('title', "Все задания")

@section('content')
    <main style=" min-height: 73vh" class="card-container all-tasks">
        <h1>{{__($service_name)}}</h1>
        @include("free_promotion.components.task-list")
    </main>
@endsection
