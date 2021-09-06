@extends('mail.layouts.base', ['lang' => $lang])

@section('content')
    <h3>{{__('Hi! Thank you for your purchase! Data for your account:')}}</h3>
    <p style="margin-bottom:30px;">{{$lang == 'en' ? 'Login' : 'Логин'}}
        <b>{{$productItem->username}}</b></p>
    <p style="margin-bottom:30px;">{{$lang == 'en' ? 'Password' : 'Пароль'}}
        <b>{{$productItem->password}}</b></p>
    <p style="margin-bottom:30px;">Port
        <b>{{$productItem->port}}</b></p>
    <p style="margin-bottom:30px;">IP
        <b>{{$productItem->ip}}</b></p>
@endsection
