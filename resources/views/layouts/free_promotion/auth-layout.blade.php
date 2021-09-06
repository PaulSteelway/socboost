@extends('layouts.template-free')

@section('title', "Задания для накрутки Инстаграм, Тик-Ток, Ютуб, Телеграм - бесплатная раскрутка в социальных сетях")

@section('description', "Выполняй задания - получай баллы")

@section('body_class', "free_promotion-admin")

@section('innerblock')

  @include('layouts.free_promotion.header')
  @include('partials.inform')


  <div class="main-customer">
    <div class="sidebar">
      @include('layouts.free_promotion.components.sidebar')
    </div>

    <div class="main-content">

      @include('layouts.free_promotion.new.mobile-nav')

      @yield('content')
    </div>
  </div>


  @include('layouts.modals')

  @include('layouts.free_promotion.modals')
  @include('layouts.free_promotion.footer')

@endsection
