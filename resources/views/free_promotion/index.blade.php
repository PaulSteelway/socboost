@extends('layouts.free_promotion.layout')

{{-- @section('title', __('site.title')) --}}

@section('content')
  <main style="padding-top: 100px; min-height: 73vh" class="home-page">

    @include('free_promotion.main.top')
    @include('free_promotion.main.like')
    @include('free_promotion.main.subscribers')
    @include('free_promotion.main.popular')

  </main>
@endsection
