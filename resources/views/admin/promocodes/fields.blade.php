<!-- Code Field -->

<!-- Reward Field -->
<div class="form-group col-md-3 col-sm-3">
    {!! Form::label('reward', __('Reward:')) !!}
    {!! Form::number('reward', null, ['class' => 'form-control']) !!}
</div>

<!-- Reward Field -->
<div class="form-group col-md-3 col-sm-3">
    {!! Form::label('data[apply_from]', __('Apply from:')) !!}
    {!! Form::number('data[apply_from]', null, ['class' => 'form-control']) !!}
</div>
@if(!isset($promocode))
    <!-- Is Disposable Field -->
    <div class="form-group col-md-3 col-sm-3">
        {!! Form::label('quantity', __('Quantity')) !!}
        {!! Form::number('quantity', null, ['class' => 'form-control']) !!}
    </div>
@endif
<!-- Is Disposable Field -->
<div class="form-group col-md-3 col-sm-3">
    {!! Form::label('is_disposable', __('Promocode quantity')) !!}
    {!! Form::number('is_disposable', null, ['class' => 'form-control']) !!}
</div>

<!-- Expires At Field -->
<div class="form-group col-md-3 col-sm-3">
    {!! Form::label('expires_at', __('Expires At:')) !!}
    {!! Form::date('expires_at', null, ['class' => 'form-control','id'=>'expires_at']) !!}
</div>

@section('scripts')
    <script type="text/javascript">
        $('#expires_at').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endsection

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('Save'), ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.promocodes.index') !!}" class="btn btn-default">{!! __('Cancel') !!}</a>
</div>
