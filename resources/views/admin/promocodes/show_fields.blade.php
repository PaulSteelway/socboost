<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $promocode->id !!}</p>
</div>

<!-- Code Field -->
<div class="form-group">
    {!! Form::label('code', 'Code:') !!}
    <p>{!! $promocode->code !!}</p>
</div>

<!-- Reward Field -->
<div class="form-group">
    {!! Form::label('reward', 'Reward:') !!}
    <p>{!! $promocode->reward !!}</p>
</div>

<!-- Data Field -->
<div class="form-group">
    {!! Form::label('data', 'Data:') !!}
    <p>{!! $promocode->data['apply_from'] !!}</p>
</div>

<!-- Is Disposable Field -->
<div class="form-group">
    {!! Form::label('is_disposable', 'Is Disposable:') !!}
    <p>{!! $promocode->is_disposable !!}</p>
</div>

<!-- Expires At Field -->
<div class="form-group">
    {!! Form::label('expires_at', 'Expires At:') !!}
    <p>{!! $promocode->expires_at !!}</p>
</div>

