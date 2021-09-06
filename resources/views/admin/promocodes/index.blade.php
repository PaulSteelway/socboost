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
{{--                        <li>--}}
{{--                            <a role="button" href="{!! route('promocodes.create') !!}">--}}
{{--                                 {{ __('Create') }}--}}
{{--                            </a>--}}
{{--                        </li>--}}
                        <li>
                            <a href="https://www.fkwallet.ru"><img src="https://www.fkwallet.ru/assets/2017/images/btns/icon_wallet1.png" title="Прием криптовалют"></a>
                            <a role="button" class="tile-fullscreen">
                                <i class="fa fa-expand"></i> {{ __('Fullscreen') }}
                            </a>

                        </li>
                    </ul>
                </div>
                <!-- /tile header -->
                <!-- tile body -->
                <div class="tile-body">

                    @include('flash::message')

                    <div class="clearfix"></div>
                    <div class="box box-primary">
                        <div class="box-body">
                            @include('admin.promocodes.table')
                        </div>
                    </div>
                    <div class="text-center">

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
