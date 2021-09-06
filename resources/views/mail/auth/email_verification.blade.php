@extends('mail.layouts.base', ['lang' => $lang])

@section('content')
    <p style="margin-bottom:30px;">{{$lang == 'en' ? 'Please click the button below to verify your email address:' : 'Пожалуйста, нажмите кнопку ниже, чтобы подтвердить свой адрес электронной почты:'}}</p>

    <p style="margin-bottom:30px;">
        <a href="{{$verificationUrl}}">
            <button style="min-width:200px;height:50px;border-radius:25px;background-color:#54b5f5;color:#ffffff;padding:15px;font-size:16px;font-weight:500;border:none;-webkit-transition:.3s ease-in;transition:.3s ease-in;cursor:pointer;">{{$lang == 'en' ? 'Yes, this is my email' : 'Да, это моя электронная почта'}}</button>
        </a>
    </p>

    <p style="margin-bottom:30px;">
        <small>{{$lang == 'en' ? 'This link will expire in 60 minutes.' : 'Срок действия этой ссылки истекает через 60 минут.'}}</small>
    </p>

    <p style="margin-bottom:30px;">
        <small>
            {{$lang == 'en' ? 'If you did not create an account, no further action is required.' : 'Если вы не создавали учетную запись, никаких дальнейших действий не требуется.'}}
        </small>
    </p>
@endsection