@extends('admin/layouts.app')
@section('title', __('Dashboard'))

@section('breadcrumbs')
    <li> {{ __('Dashboard') }}</li>
@endsection
@section('content')

    {{-- Перенесено на другую страницу --}}
    <div class="row">
      <div class="col-sm-12">
        <a href="{{ route('admin1') }}">
          Статистика тут
        </a>
      </div>

      <div class="col-sm-12">
        <a href="{{ route('admin2') }}">
          Графики тут
        </a>
        и
        <a href="{{ route('admin.statistic') }}">
          тут
        </a>

        <small>
          (перенесено для снятия нагрузки с главной, могут не открываться из-за кучи данных)
        </small>
      </div>
    </div>

@endsection
