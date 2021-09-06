@extends('layouts.template-free')

@section('title', "Бесплатная накрутка Инстаграм, Тик-Ток, Ютуб, Телеграм - бесплатная раскрутка в социальных сетях")

@section('description', "Раскрути Инстаграм, Тик-Ток, Ютуб, Телеграм - бесплатная раскрутка в социальных сетях")

@section('body_class', "free_promotion")

@section('innerblock')

  @include('layouts.free_promotion.header')
  @include('partials.inform')

  @yield('content')

  @include('layouts.modals')

  @include('layouts.free_promotion.modals')
  @include('layouts.free_promotion.footer')

@endsection
