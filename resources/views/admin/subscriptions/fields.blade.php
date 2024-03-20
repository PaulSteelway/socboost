<div class="col-md-12">
    <div class="row">
        <!-- User Field -->
        <div class="form-group col-sm-4">
            {!! Form::label('user_id', __('User')) !!}
            <div class="form-control" disabled>
                <a href="{{route('admin.users.show', $subscription->user_id)}}"
                   target="_blank">{{$subscription->user->email}}</a>
            </div>
        </div>

        <!-- Subscription Field -->
        <div class="form-group col-sm-4">
            {!! Form::label('subscription_id', __('Subscription ID')) !!}
            <div class="form-control" disabled>{{$subscription->subscription_id}}</div>
        </div>

        <!-- Status Field -->
        <div class="form-group col-sm-4">
            {!! Form::label('status', 'Status') !!}
            <div>
                <span class="label label-{{['New' => 'primary', 'Active' => 'success', 'close' => 'Default', 'Error' => 'danger','Cancelled' => 'default'][$subscription->status]}}"
                      style="font-size: 14px; font-weight: 600;">{{$subscription->status}}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Type Field -->
        <div class="form-group col-sm-4">
            {!! Form::label('type', __('Type')) !!}
            <div class="form-control" disabled>{{$subscription->type}}</div>
        </div>

        <!-- Date At Field -->
        <div class="form-group col-sm-4">
            {!! Form::label('date_at', __('Expiry')) !!}
            <div class="form-control" disabled>{{(new \Carbon\Carbon($subscription->date_at))->format('Y-m-d')}}</div>
        </div>

        <!-- Period Field -->
        <div class="form-group col-sm-4">
            {!! Form::label('period', __('Period')) !!}
            {!! Form::number('period', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="row">
        <!-- Payment Method Field -->
        <div class="form-group col-sm-4">
            {!! Form::label('payment_method', __('Payment Method')) !!}
            <div class="form-control" disabled>{{$subscription->payment_method}}</div>
        </div>

        <!-- Currency Field -->
        <div class="form-group col-sm-4">
            {!! Form::label('currency_id', __('Currency')) !!}
            <div class="form-control" disabled>{{$subscription->currency->code}}</div>
        </div>

        <!-- IP Field -->
        <div class="form-group col-sm-4">
            {!! Form::label('ip', __('IP')) !!}
            <div class="form-control" disabled>{{$subscription->ip}}</div>
        </div>
    </div>

    @if ($subscription->type == 'Order')
        <div class="row">
            <!-- Packet Id Field -->
            <div class="form-group col-sm-8">
                {!! Form::label('packet_id', __('Packet')) !!}
                <div class="form-control" disabled>
                    <a href="{{route('admin.packets.edit', $subscription->packet_id)}}"
                       target="_blank">{{$subscription->packet->name_en}}</a>
                </div>
            </div>

            <!-- Username Field -->
            <div class="form-group col-sm-4">
                {!! Form::label('username', __('Username')) !!}
                <div class="form-control" disabled>{{$subscription->username}}</div>
            </div>

            <!-- Posts Field -->
            <div class="form-group col-sm-4">
                {!! Form::label('posts', __('Posts')) !!}
                <div class="form-control" disabled>{{$subscription->posts}}</div>
            </div>

            <!-- Qty Min Field -->
            <div class="form-group col-sm-4">
                {!! Form::label('qty_min', __('Quantity Min')) !!}
                <div class="form-control" disabled>{{$subscription->qty_min}}</div>
            </div>

            <!-- Qty Max Field -->
            <div class="form-group col-sm-4">
                {!! Form::label('qty_max', __('Quantity Max')) !!}
                <div class="form-control" disabled>{{$subscription->qty_max}}</div>
            </div>
        </div>
    @endif
</div>

<!-- Note Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('note', __('Note')) !!}
    {!! Form::textarea('note', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-default">Cancel</a>
</div>
