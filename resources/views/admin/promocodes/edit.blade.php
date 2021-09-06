@extends('admin/layouts.app')
@section('title')
    {{ __('Promocodes') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Promocodes') }}</li>
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <!-- col -->
        <div class="col-md-12">
            <!-- tile -->
            <section class="tile">
                <!-- tile header -->
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">{{ __('Promocodes') }}</h1>
                    <ul class="controls">
                        <li>
                            <a role="button" class="tile-fullscreen">
                                <i class="fa fa-expand"></i> {{ __('Fullscreen') }}
                            </a>

                        </li>
                    </ul>
                </div>
                <!-- /tile header -->
                <!-- tile body -->
                <div class="tile-body">

                    <div class="content">
                        @include('adminlte-templates::common.errors')
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">
                                    {!! Form::model($promocode, ['route' => ['admin.promocodes.update', $promocode->id], 'method' => 'patch']) !!}

                                    @include('admin.promocodes.fields')

                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /tile body -->
            </section>
            <!-- /tile -->
        </div>
        <!-- /col -->
    </div>
    <!-- /row -->
@endsection

