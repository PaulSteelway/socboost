@extends('admin/layouts.app')

@section('title')
    {{ __('Product item') }}
@endsection

@section('breadcrumbs')
    <li> {{ __('Product item') }}</li>
@endsection

@section('content')
    @include('flash::message')
    <!-- row -->
    <div class="row">
        <!-- col -->
        <div class="col-md-12">
            <!-- tile -->
            <section class="tile">
                <!-- tile header -->
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">{{ __('Product item') }}</h1>
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
                        <div class="box box-primary">

                            <div class="box-body">
                                <button id="product_item_new_element">Добавить поле</button>
                                <div class="row">
                                    {!! Form::open(['route' => 'admin.productItems.store', 'files' => true]) !!}

                                    @include('admin.product_items.fields')

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
