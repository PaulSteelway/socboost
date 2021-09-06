@extends('layouts.profile')

@section('title', __('Operations') . ' - ' . __('site.site'))

@section('content')
  <div class="row" style="padding:30px 0;">
    @include('partials.inform')

    <div class="panel panel-default">
      <div class="panel-heading ui-draggable-handle">
        @if($status === 'success')
          <h3 class="panel-title">{{ __('All operations processing automatically. It can take up to couple of hours.') }}</h3>
        @else
          <h3 class="panel-title">{{ __('Payment canceled') }}</h3>
        @endif
      </div>
    </div>

  </div>
@endsection
