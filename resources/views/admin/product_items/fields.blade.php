<!-- Name Field -->

<div class="form-group product-item-group">
    <div class="input-group product-item-field col-sm-6">
        <div class="col-sm-6">Ключ</div>
        <div class="col-sm-6">Значение</div>
        {!! Form::text('keys[0]', 'username', ['class' => 'form-control product-item-element', 'readonly']) !!}
        {!! Form::text('values[0]', null, ['class' => 'form-control product-item-element']) !!}
    </div>
    <div class="input-group product-item-field col-sm-6">
        {!! Form::text('keys[1]', 'password', ['class' => 'form-control product-item-element', 'readonly']) !!}
        {!! Form::text('values[1]', null, ['class' => 'form-control product-item-element']) !!}
    </div>
</div>
<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password_field', 'Password:') !!}
    {!! Form::text('password_field', null, ['class' => 'form-control']) !!}
</div>


<!-- Category Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('product_id', 'Product Id:') !!}
    {!! Form::select('product_id', $products, null, ['class' => 'form-control', 'placeholder' => '']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.productItems.index') }}" class="btn btn-default">Cancel</a>
</div>