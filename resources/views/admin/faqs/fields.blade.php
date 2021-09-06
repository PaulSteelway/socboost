<!-- Category Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('category_id', __('Category')) !!}
    {!! Form::select('category_id', $categories, null, ['class' => 'form-control',  'placeholder' => 'Select category...']) !!}
</div>

<!-- Order Field -->
<div class="form-group col-sm-2">
    {!! Form::label('order', __('Order')) !!}
    {!! Form::number('order', null, ['class' => 'form-control', 'min' => 1, 'step' => 1]) !!}
</div>

<!-- Question Ru Field -->
<div class="form-group col-sm-6">
    {!! Form::label('question_ru', __('Question (RU)')) !!}
    {!! Form::text('question_ru', null, ['class' => 'form-control']) !!}
</div>

<!-- Question En Field -->
<div class="form-group col-sm-6">
    {!! Form::label('question_en', __('Question (EN)')) !!}
    {!! Form::text('question_en', null, ['class' => 'form-control']) !!}
</div>

<!-- Answer Ru Field -->
<div class="form-group col-sm-6 col-lg-6">
    {!! Form::label('answer_ru', __('Answer (RU)')) !!}
    {!! Form::textarea('answer_ru', null, ['class' => 'form-control']) !!}
</div>

<!-- Answer En Field -->
<div class="form-group col-sm-6 col-lg-6">
    {!! Form::label('answer_en', __('Answer (EN)')) !!}
    {!! Form::textarea('answer_en', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.faqs.index') }}" class="btn btn-default">Cancel</a>
</div>
