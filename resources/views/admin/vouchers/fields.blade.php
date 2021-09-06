<!-- Code Field -->
{{--<div class="form-group col-sm-6">--}}
{{--    {!! Form::label('code', 'Code:') !!}--}}
{{--    {!! Form::text('code', null, ['class' => 'form-control']) !!}--}}
{{--</div>--}}

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('amount', 'Amount:') !!}
    {!! Form::number('amount', null, ['class' => 'form-control', 'min' => 1, 'step' => 1]) !!}
</div>

<!-- Currency Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('currency_id', 'Currency:') !!}
    {!! Form::select('currency_id', $currencies, null, ['class' => 'form-control',  'placeholder' => 'Select currency...']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-default">Cancel</a>
</div>
