@extends('admin/layouts.app')

@section('title')
    {{ __('Ticket Message') }}
@endsection

@section('breadcrumbs')
    <li> {{ __('Ticket Message') }}</li>
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
                    <h1 class="custom-font">{{ __('Response on Ticket') }} №{{$ticket_id}}</h1>
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
                                    {!! Form::model($ticketMessage, ['route' => ['admin.ticketMessages.update', $ticketMessage->id], 'method' => 'patch']) !!}
                                    @include('admin.ticket_messages.fields')
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
