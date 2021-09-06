@extends('admin/layouts.app')

@section('title')
    {{ __('Tickets') }}
@endsection

@section('breadcrumbs')
    <li> {{ __('Tickets') }}</li>
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
                    <h1 class="custom-font">{{ __('Tickets') }}</h1>
                    <ul class="controls">
                        <li>
                            <a role="button" class="btn-primary" style="color: white;"
                               href="{!! route('admin.ticketMessages.create', ['ticket_id' => $ticket->id]) !!}">
                                {{ __('Add response') }}
                            </a>
                        </li>
{{--                        <li>--}}
{{--                            <a role="button" class="tile-fullscreen">--}}
{{--                                <i class="fa fa-expand"></i> {{ __('Fullscreen') }}--}}
{{--                            </a>--}}
{{--                        </li>--}}
                    </ul>
                </div>
                <!-- /tile header -->
                <!-- tile body -->
                <div class="tile-body">
                    <div class="content">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">
                                    {!! Form::model($ticket, ['route' => ['admin.tickets.update', $ticket->id], 'method' => 'patch']) !!}
                                    @include('admin.tickets.fields')
                                    {!! Form::close() !!}
                                </div>
                                @foreach($messages as $message)
                                    <div class="ticket_block" style="background: {{ $ticket->user_id == $message->user_id ? '#B0E0E6' : '#eee' }}">
                                        <div class="ticket_title">{{$message->user->email}}</div>
                                        <div class="ticket_title pull-right">
                                            {{$message->created_at}}
                                            {!! Form::open(['route' => ['admin.ticketMessages.destroy', $message->id], 'method' => 'delete', 'style' => 'display: inline-block; padding-left: 10px;']) !!}
                                            <a href="{{ route('admin.ticketMessages.edit', $message->id, $ticket->id) }}" class='btn btn-primary btn-xs'>
                                                <i class="glyphicon glyphicon-edit"></i>
                                            </a>
                                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
                                                'type' => 'submit',
                                                'class' => 'btn btn-danger btn-xs',
                                                'onclick' => "return confirm('Are you sure?')"
                                            ]) !!}
                                            {!! Form::close() !!}
                                        </div>
                                        <div>{{$message->message}}</div>
                                    </div>
                                @endforeach
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
