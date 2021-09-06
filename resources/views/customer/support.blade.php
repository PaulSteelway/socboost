@extends('layouts.customer')

@section('title', "Поддержка" . ' - ' . __('site.site'))

@section('content')
    
<div class="row" style="padding:130px 0 100px;">
    <div class="col-lg-3"></div>
    <div class="col-lg-7">
        @include('partials.inform')
        <form method="POST" target="_top" action="{{ route('customer.support') }}">
            {{ csrf_field() }}

            <div class="form-group">
                <label>{{ __("Your email:") }}</label>
                <input type="text" name="email" class="form-control">
            </div>
            <div class="form-group" style="margin-bottom:15px">
                <label>{{ __("Text:") }}</label>
                <textarea class="form-control" name="text"></textarea>
            </div>

            <div class="form-group" style="text-align: right;">
                <input type="submit" value="Submit" class="btn btn-success">
            </div>
        </form>
    </div>
</div>
@endsection
