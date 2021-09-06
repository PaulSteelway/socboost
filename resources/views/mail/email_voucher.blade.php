@extends('mail.layouts.base', ['lang' => $lang])

@section('content')
    <p style="margin-bottom:30px;">{{$lang == 'en' ? 'Your voucher code: ' : 'Ваш код ваучера: '}}
        <b>{{$voucher->code}}</b></p>
@endsection