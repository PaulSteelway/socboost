@extends('mail.layouts.base', ['lang' => $lang])

@section('content')
    <p style="margin-bottom:30px;">
        @if($lang == 'en')
            Your account credentials:<br>
            Email: <b>{{$user->email}}</b><br>
            Password: <b>{{$password}}</b>
        @else
            Ваши учетные данные:<br>
            Электронная почта: <b>{{$user->email}}</b><br>
            Пароль: <b>{{$password}}</b>
        @endif
    </p>

    <p style="margin-bottom:30px;">{{$lang == 'en' ? 'You can edit your account by clicking the button below:' : 'Вы можете изменить настройки аккаунта, нажав кнопку ниже:'}}</p>

    <p style="margin-bottom:30px;">
        <a href="{{$settingsUrl}}">
            <button style="min-width:200px;height:50px;border-radius:25px;background-color:#54b5f5;color:#ffffff;padding:15px;font-size:16px;font-weight:500;border:none;-webkit-transition:.3s ease-in;transition:.3s ease-in;cursor:pointer;">{{$lang == 'en' ? 'Change password' : 'Изменить пароль'}}</button>
        </a>
    </p>

    <p style="margin-bottom:30px;">
        <small>
            {{$lang == 'en' ? 'If you did not create an account, no further action is required.' : 'Если вы не создавали учетную запись, никаких дальнейших действий не требуется.'}}
        </small>
    </p>
@endsection