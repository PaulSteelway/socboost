<!-- Category Field -->
<div class="form-group col-sm-12">
    {!! Form::label('category_id', 'Category:') !!}
    {!! Form::select('category_id', $categories, null, ['class' => 'form-control',  'placeholder' => 'Select category...']) !!}
</div>

<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Title En:') !!}
    {!! Form::text('title', null, ['class' => 'form-control']) !!}
</div>

<!-- Title Ru Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title_ru', 'Title Ru:') !!}
    {!! Form::text('title_ru', null, ['class' => 'form-control']) !!}
</div>

<!-- Title Es Field -->
{{--<div class="form-group col-sm-6">--}}
{{--    {!! Form::label('title_es', 'Title Es:') !!}--}}
{{--    {!! Form::text('title_es', null, ['class' => 'form-control']) !!}--}}
{{--</div>--}}

<!-- Meta Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('meta_title', 'Meta Title En:') !!}
    {!! Form::text('meta_title', null, ['class' => 'form-control']) !!}
</div>

<!-- Meta Title Ru Field -->
<div class="form-group col-sm-6">
    {!! Form::label('meta_title_ru', 'Meta Title Ru:') !!}
    {!! Form::text('meta_title_ru', null, ['class' => 'form-control']) !!}
</div>

<!-- Meta Title Es Field -->
{{--<div class="form-group col-sm-6">--}}
{{--    {!! Form::label('meta_title_es', 'Meta Title Es:') !!}--}}
{{--    {!! Form::text('meta_title_es', null, ['class' => 'form-control']) !!}--}}
{{--</div>--}}

<!-- Meta Keywords Field -->
<div class="form-group col-sm-6 col-lg-6">
    {!! Form::label('meta_keywords', 'Meta Keywords En:') !!}
    {!! Form::textarea('meta_keywords', null, ['class' => 'form-control']) !!}
</div>

<!-- Meta Keywords Ru Field -->
<div class="form-group col-sm-6 col-lg-6">
    {!! Form::label('meta_keywords_ru', 'Meta Keywords Ru:') !!}
    {!! Form::textarea('meta_keywords_ru', null, ['class' => 'form-control']) !!}
</div>

<!-- Meta Keywords Es Field -->
{{--<div class="form-group col-sm-6 col-lg-6">--}}
{{--    {!! Form::label('meta_keywords_es', 'Meta Keywords Es:') !!}--}}
{{--    {!! Form::textarea('meta_keywords_es', null, ['class' => 'form-control']) !!}--}}
{{--</div>--}}

<!-- Meta Description Field -->
<div class="form-group col-sm-6 col-lg-6">
    {!! Form::label('meta_description', 'Meta Description En:') !!}
    {!! Form::textarea('meta_description', null, ['class' => 'form-control']) !!}
</div>

<!-- Meta Description Ru Field -->
<div class="form-group col-sm-6 col-lg-6">
    {!! Form::label('meta_description_ru', 'Meta Description Ru:') !!}
    {!! Form::textarea('meta_description_ru', null, ['class' => 'form-control']) !!}
</div>

<!-- Meta Description Es Field -->
{{--<div class="form-group col-sm-6 col-lg-6">--}}
{{--    {!! Form::label('meta_description_es', 'Meta Description Es:') !!}--}}
{{--    {!! Form::textarea('meta_description_es', null, ['class' => 'form-control']) !!}--}}
{{--</div>--}}

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.categoryAddPages.index') }}" class="btn btn-default">Cancel</a>
</div>
