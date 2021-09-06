@php
  $mail_lang = app()->getLocale();

  if (isset($lang)) {
    $mail_lang = $lang;
  }
@endphp


@extends('mail.layouts.base', ['lang' => $mail_lang])

@section('content')
    {{-- Intro Lines --}}
    @foreach ($introLines as $line)
        <p style="margin-bottom:30px;">{{ $line }}</p>
    @endforeach

    {{-- Action Button --}}
    <p style="margin-bottom:30px;">
        <a href="{{$actionUrl}}">
            <button style="min-width:200px;height:50px;border-radius:25px;background-color:#54b5f5;color:#ffffff;padding:15px;font-size:16px;font-weight:500;border:none;-webkit-transition:.3s ease-in;transition:.3s ease-in;cursor:pointer !important;">{{$actionText}}</button>
        </a>
    </p>

    {{-- Outro Lines --}}
    @foreach ($outroLines as $line)
        <p style="margin-bottom:30px;"><small>{{ $line }}</small></p>
    @endforeach
@endsection
