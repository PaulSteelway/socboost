@extends('admin/layouts.app')

@section('title')
    {{ __('Blog Category') }}
@endsection

@section('breadcrumbs')
    <li> {{ __('Blog Category') }}</li>
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
                    <h1 class="custom-font">{{ __('Categories') }}</h1>
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
                                    {!! Form::open(['route' => 'admin.blog_categories.store', 'files' => true]) !!}

                                    @include('admin.blog_categories.fields')

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
