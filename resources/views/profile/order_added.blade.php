@extends('layouts.profile')

@section('title', 'Пополнение баланса' . ' - ' . __('site.site'))

@section('content')
    <div class="row" style="padding:30px 0;">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            @include('partials.inform')
        </div>
    </div>
@endsection
