@extends('layouts.template')

@section('body_class', "homepage")

@section('innerblock')

  @include('partials.inform')

  @yield('content')

  @include('layouts.modals')
  @include('layouts.footer')

@endsection
