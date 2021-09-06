@extends('layouts.customer')

@section('title', __('Two factor authentication') . ' - ' . __('site.site'))

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading"></div>
          <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('auth.tfa.update') }}">
              {{ csrf_field() }}
              <button type="submit" class="btn btn-primary">{{ $data['tfa_token'] ? 'deactivate' : 'activate'}}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
