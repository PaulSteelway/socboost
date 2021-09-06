@extends('admin/layouts.app')

@section('title')
    {{ __('Category Additional Pages') }}
@endsection

@section('breadcrumbs')
    <li> {{ __('Category Additional Pages') }}</li>
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
                    <h1 class="custom-font">{{ __('Category Additional Pages') }}</h1>
                    <ul class="controls">
                        <li>
                            <a role="button" href="{!! route('admin.categoryAddPages.create') !!}">
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
                <div class="tile-body">

                    @include('flash::message')

                    <div class="clearfix"></div>
                    <div class="box box-primary">
                        <div class="box-body">
                            @include('admin.category_add_pages.table')
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
