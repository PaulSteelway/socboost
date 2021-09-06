@extends('mail.layouts.base', ['lang' => $lang])


@section('content')
    {!! html_entity_decode($html) !!}
@endsection
