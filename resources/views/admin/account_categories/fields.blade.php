
<!-- Name Field -->
<div class="form-group col-sm-5 col-lg-5">
    {!! Form::label('name_ru', 'Name (RU):') !!}
    {!! Form::text('name_ru', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-5 col-lg-5">
    {!! Form::label('name_en', 'Name (EN):') !!}
    {!! Form::text('name_en', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-2 col-lg-2">
    {!! Form::label('order', 'Order:') !!}
    {!! Form::text('order', null, ['class' => 'form-control']) !!}
</div>
<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Title (EN):') !!}
    {!! Form::text('title', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('title_ru', 'Title (RU):') !!}
    {!! Form::text('title_ru', null, ['class' => 'form-control']) !!}
</div>
<!-- Meta Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('meta_title', 'Meta Title (EN):') !!}
    {!! Form::text('meta_title', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('meta_title_ru', 'Meta Title (RU):') !!}
    {!! Form::text('meta_title_ru', null, ['class' => 'form-control']) !!}
</div>
<!-- Meta Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('meta_description', 'Meta Description (EN):') !!}
    {!! Form::text('meta_description', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('meta_description_ru', 'Meta Description (RU):') !!}
    {!! Form::text('meta_description_ru', null, ['class' => 'form-control']) !!}
</div>

<!-- Meta Keywords Fields -->
<div class="form-group col-sm-6">
    {!! Form::label('meta_keywords', 'Meta Keywords (EN):') !!}
    {!! Form::text('meta_keywords', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('meta_keywords_ru', 'Meta Keywords (RU):') !!}
    {!! Form::text('meta_keywords_ru', null, ['class' => 'form-control']) !!}
</div>

<!-- icon_class Field -->
<div class="form-group col-sm-3 col-lg-3">
    {!! Form::label('icon_class', 'Icon Class:') !!}
    {!! Form::text('icon_class', null, ['class' => 'form-control']) !!}
</div>
<!-- icon_class Field -->
<div class="form-group col-sm-3 col-lg-3" style="min-height: 59px">
    {!! Form::label('icon_img', 'Icon Image:') !!}
    <div style="display: flex">
        {!! Form::file('icon_img') !!}
        @if(!empty($category->icon_img))
            <img src="/{{$category->icon_img}}" width="24" alt="">
        @endif
    </div>
</div>

<!-- icon_class Field -->
<div class="form-group col-sm-3 col-lg-3" style="min-height: 59px">
    {!! Form::label('icon_img_active', 'Icon Image Active:') !!}
    <div style="display: flex">
        {!! Form::file('icon_img_active') !!}
        @if(!empty($category->icon_img_active))
            <img src="/{{$category->icon_img_active}}" width="24" alt="">
        @endif
    </div>
</div>


<!-- URL Field -->
<div class="form-group col-sm-3">
    {!! Form::label('url', 'URL:') !!}
    {!! Form::text('url', null, ['class' => 'form-control']) !!}
</div>


<!-- Details Ru Field -->
<div class="form-group col-sm-6 col-lg-6">
    {!! Form::label('details_ru', 'Details (RU):') !!}
    {!! Form::textarea('details_ru', null, ['class' => 'form-control']) !!}
</div>

<!-- Details En Field -->
<div class="form-group col-sm-6 col-lg-6">
    {!! Form::label('details_en', 'Details (EN):') !!}
    {!! Form::textarea('details_en', null, ['class' => 'form-control']) !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.accountCategories.index') !!}" class="btn btn-default">Cancel</a>
</div>

<script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('details_ru');
    CKEDITOR.replace('details_en');
</script>
