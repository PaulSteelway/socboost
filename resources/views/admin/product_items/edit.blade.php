


@extends('admin/layouts.app')

@section('title')
    {{ __('Product Item') }}
@endsection

@section('breadcrumbs')
    <li> {{ __('Product Item') }}</li>
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
                    <h1 class="custom-font">{{ __('Product Item') }}</h1>
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
                                <div class="row">
                                    {!! Form::model($productItem, ['route' => ['admin.productItems.update', $productItem->id], 'method' => 'patch', 'files' => true]) !!}

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
