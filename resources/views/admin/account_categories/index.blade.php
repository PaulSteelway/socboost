@extends('admin/layouts.app')

@section('title')
    {{ __('Subscriptions') }}
@endsection

@section('breadcrumbs')
    <li>{{ __('Subscriptions') }}</li>
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
                    <h1 class="custom-font">{{ __('Ready account') }}</h1>
                    <ul class="controls">
                        <li>
                            <a role="button" href="{!! route('admin.accountCategories.create') !!}">
                                {{ __('Create') }}
                            </a>
                        </li>
                        <li>
                            <a role="button" class="tile-fullscreen">
                                <i class="fa fa-expand"></i> {{ __('Fullscreen') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /tile header -->
                <!-- tile body -->
                @include('flash::message')

                <div class="tile-body">
                    <ul class="nav nav-tabs" style="margin-bottom: 10px">
                        <li class="active"><a  href="/admin/accountCategories">Categories</a></li>
                        <li><a  href="/admin/products">Products</a></li>
                        <li><a  href="/admin/productItems">Products items</a></li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="box box-primary">
                        <div class="box-body">
                            @include('admin.default_table')
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
