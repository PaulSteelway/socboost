<!-- Name Ru Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name_ru', __('Name (RU):')) !!}
    {!! Form::text('name_ru', null, ['class' => 'form-control']) !!}
</div>

<!-- Name En Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name_en', __('Name (EN):')) !!}
    {!! Form::text('name_en', null, ['class' => 'form-control']) !!}
</div>

<!-- Order Field -->
<div class="form-group col-sm-2">
    {!! Form::label('order', 'Order:') !!}
    {!! Form::number('order', null, ['class' => 'form-control', 'min' => 1, 'step' => 1]) !!}
</div>


<!-- Icon Field -->
<div class="form-group col-sm-5 col-lg-5" style="min-height: 59px">
    {!! Form::label('icon', __('Icon:')) !!}
    <div style="display: flex">
        {!! Form::file('icon') !!}
        @if (!empty($faqCategory) && !empty($faqCategory->icon))
            <img src="/{{$faqCategory->icon}}" width="24" alt="">
        @endif
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.faqCategories.index') }}" class="btn btn-default">Cancel</a>
</div>
