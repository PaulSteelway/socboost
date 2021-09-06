@extends('admin/layouts.app')

@section('title')
    {{ __('Packets') }}
@endsection

@section('breadcrumbs')
    <li> {{ __('Packets') }}</li>
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
                    <h1 class="custom-font">{{ __('Packets') }}</h1>
                    <ul class="controls">
                        <li>
                            <div class="packets-btn-actions">
                                {{ csrf_field() }}
                                <button type="button" class="btn btn-danger" id="pbm" style="padding: 6px 12px">
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                </button>
                                <input type="number" class="form-control" min="0" max="100" step="0.01" id="pb-input"
                                       placeholder="%">
                                <button type="button" class="btn btn-success" id="pbp" style="padding: 6px 12px">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </button>
                            </div>
                        </li>
                        <li>
                            <a role="button" href="{!! route('admin.packets.create') !!}">
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
                            @include('admin.packets.table')
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

