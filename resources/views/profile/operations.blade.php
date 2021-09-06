@extends('layouts.profile')

@section('title', __('Operations') . ' - ' . __('site.site'))

@section('content')
    <main style="padding-top: 100px; min-height: 86vh">
        @include('profile.operationsTable')
    </main>
@endsection
