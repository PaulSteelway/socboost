@extends('layouts.template')

@section('body_class', "homepage")

@section('innerblock')

  @include('layouts.header')

  @yield('content')

  @include('layouts.modals')
  @include('layouts.footer')

  @push('scripts')
    {!! Toastr::render() !!}
  @endpush
@endsection
