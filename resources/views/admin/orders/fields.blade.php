<!-- Order Id Field -->
<div class="form-group col-sm-3">
    {!! Form::label('order_id', 'Order ID:') !!}
    <div class="form-control" disabled>
        {{$order->order_id}}
    </div>
</div>

<!-- User Id Field -->
<div class="form-group col-sm-3">
    {!! Form::label('user_id', 'User:') !!}
    <div class="form-control" disabled>
        <a href="{{route('admin.users.show', $order->user_id)}}"
           target="_blank">{{ empty($order->user->email) ? $order->user->phone : $order->user->email }}</a>
    </div>
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    <div class="form-control" disabled>
        {{$order->name}}
    </div>
</div>

<!-- Jap Id Field -->
<div class="form-group col-sm-3">
    {!! Form::label('jap_id', 'API ID:') !!}
    @if(empty($order->jap_id))
        {!! Form::text('jap_id', null, ['class' => 'form-control']) !!}
    @else
        <div class="form-control" disabled>
            {{$order->jap_id}}
        </div>
    @endif
</div>

<!-- Jap Status Field -->
<div class="form-group col-sm-3">
    {!! Form::label('jap_status', 'API Status:') !!}
    {!! Form::select('jap_status', ([null => '-'] + array_combine(config('enumerations.order_jap_statuses'), config('enumerations.order_jap_statuses'))), null, ['class' => 'form-control']) !!}
</div>

<!-- Packet Field -->
<div class="form-group col-sm-6">
    {!! Form::label('packet_id', 'Packet:') !!}
    <div class="form-control" disabled>
        @if(!empty($order->packet_id))
            <a href="{{route('admin.packets.edit', $order->packet_id)}}"
               target="_blank">{{ $order->packet->name_en }}</a>
        @endif
    </div>
</div>

<!-- Link Field -->
<div class="form-group col-sm-6">
    {!! Form::label('link', 'Link:') !!}
    <div class="form-control" disabled>
        {{$order->link}}
    </div>
</div>

<!-- Quantity Field -->
<div class="form-group col-sm-3">
    {!! Form::label('quantity', 'Quantity:') !!}
    <div class="form-control" disabled>
        {{$order->quantity}}
    </div>
</div>

<!-- Price Field -->
<div class="form-group col-sm-3">
    {!! Form::label('price', 'Price:') !!}
    <div class="form-control" disabled>
        {{$order->price}}
    </div>
</div>

<!-- Username Field -->
<div class="form-group col-sm-4">
    {!! Form::label('username', 'Username:') !!}
    <div class="form-control" disabled>
        {{$order->username}}
    </div>
</div>

<!-- Min Field -->
<div class="form-group col-sm-4">
    {!! Form::label('min', 'Min:') !!}
    <div class="form-control" disabled>
        {{$order->min}}
    </div>
</div>

<!-- Max Field -->
<div class="form-group col-sm-4">
    {!! Form::label('max', 'Max:') !!}
    <div class="form-control" disabled>
        {{$order->max}}
    </div>
</div>

<!-- Subscription Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('subscription_id', 'Subscription:') !!}
    <div class="form-control" disabled>
        @if(!empty($order->subscription))
            <a href="{{route('admin.packets.edit', $order->subscription_id)}}"
               target="_blank">{{ $order->subscription->subscription_id }}</a>
        @endif
    </div>
</div>

<!-- Expiry Field -->
<div class="form-group col-sm-3">
    {!! Form::label('expiry', 'Expiry:') !!}
    <div class="form-control" disabled>
        {{$order->expiry}}
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.users.show', $order->user_id) }}" class="btn btn-default">Cancel</a>
</div>
