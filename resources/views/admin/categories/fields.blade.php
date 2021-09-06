<!-- Name Field -->
<div class="form-group col-sm-5">
    {!! Form::label('name_ru', 'Name (RU):') !!}
    {!! Form::text('name_ru', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-5">
    {!! Form::label('name_en', 'Name (EN):') !!}
    {!! Form::text('name_en', null, ['class' => 'form-control']) !!}
</div>
<!-- Title Field -->
<div class="form-group col-sm-5">
    {!! Form::label('title', 'Title (EN):') !!}
    {!! Form::text('title', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-5">
    {!! Form::label('title_ru', 'Title (RU):') !!}
    {!! Form::text('title_ru', null, ['class' => 'form-control']) !!}
</div>
<!-- Meta Title Field -->
<div class="form-group col-sm-5">
    {!! Form::label('meta_title', 'Meta Title (EN):') !!}
    {!! Form::text('meta_title', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-5">
    {!! Form::label('meta_title_ru', 'Meta Title (RU):') !!}
    {!! Form::text('meta_title_ru', null, ['class' => 'form-control']) !!}
</div>
<!-- Meta Description Field -->
<div class="form-group col-sm-5">
    {!! Form::label('meta_description', 'Meta Description (EN):') !!}
    {!! Form::text('meta_description', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-5">
    {!! Form::label('meta_description_ru', 'Meta Description (RU):') !!}
    {!! Form::text('meta_description_ru', null, ['class' => 'form-control']) !!}
</div>

<!-- Meta Keywords Fields -->
<div class="form-group col-sm-5">
    {!! Form::label('meta_keywords', 'Meta Keywords (EN):') !!}
    {!! Form::text('meta_keywords', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-5">
    {!! Form::label('meta_keywords_ru', 'Meta Keywords (RU):') !!}
    {!! Form::text('meta_keywords_ru', null, ['class' => 'form-control']) !!}
</div>

<!-- Order Field -->
<div class="form-group col-sm-3">
    {!! Form::label('order', 'Order:') !!}
    {!! Form::number('order', null, ['class' => 'form-control', 'min' => 1, 'step' => 1]) !!}
</div>

<!-- icon_class Field -->
<div class="form-group col-sm-4 col-lg-4">
    {!! Form::label('icon_class', 'Icon Class:') !!}
    {!! Form::text('icon_class', null, ['class' => 'form-control']) !!}
</div>
<!-- icon_class Field -->
<div class="form-group col-sm-4 col-lg-4" style="min-height: 59px">
    {!! Form::label('icon_img', 'Icon Image:') !!}
    <div style="display: flex">
        {!! Form::file('icon_img') !!}
        @if(!empty($category->icon_img))
            <img src="/{{$category->icon_img}}" width="24" alt="">
        @endif
    </div>
</div>

<!-- icon_class Field -->
<div class="form-group col-sm-4 col-lg-3" style="min-height: 59px">
    {!! Form::label('icon_img_active', 'Icon Image Active:') !!}
    <div style="display: flex">
        {!! Form::file('icon_img_active') !!}
        @if(!empty($category->icon_img_active))
            <img src="/{{$category->icon_img_active}}" width="24" alt="">
        @endif
    </div>
</div>

<!-- Parent Field -->
<div class="form-group col-sm-4 col-lg-3">
    {!! Form::label('parent_id', 'Parent:') !!}
    {!! Form::select('parent_id', $parents, null, ['id' => 'sub_cat_select', 'onchange' => 'checkCategory()','class' => 'form-control', 'placeholder' => '<<MAIN CATEGORY>>']) !!}
</div>

<!-- Parent Field -->
<div class="form-group col-sm-2 col-lg-2" style="flex-direction: column; display: flex;">
    {!! Form::label('free_promotion', 'Free promotion:') !!}
    {!! Form::checkbox('free_promotion', '1', null) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-2">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::select('type',  array_combine(config('enumerations.category_types'), config('enumerations.category_types')), null, ['class' => 'form-control']) !!}
</div>




<div id="sub_category_fields" style="display: none">

    <!-- URL Field -->
    <div class="form-group col-sm-3">
        {!! Form::label('url', 'URL:') !!}
        {!! Form::text('url', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-3">
        {!! Form::label('icon_title1', 'Icon 1 Title:') !!}
        {!! Form::text('icon_title1', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('icon_title1_ru', 'Icon 1 Title RUS:') !!}
        {!! Form::text('icon_title1_ru', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('icon_title2', 'Icon 2 Title:') !!}
        {!! Form::text('icon_title2', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('icon_title2_ru', 'Icon 2 Title RUS:') !!}
        {!! Form::text('icon_title2_ru', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('icon_title3', 'Icon 3 Title:') !!}
        {!! Form::text('icon_title3', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('icon_title3_ru', 'Icon 3 Title RUS:') !!}
        {!! Form::text('icon_title3_ru', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('icon_title4', 'Icon 4 Title:') !!}
        {!! Form::text('icon_title4', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('icon_title4_ru', 'Icon 4 Title RUS:') !!}
        {!! Form::text('icon_title4_ru', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-3">
        {!! Form::label('icon_subtitle1', 'Icon 1 Sub title:') !!}
        {!! Form::text('icon_subtitle1', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('icon_subtitle1_ru', 'Icon 1 Sub title RUS:') !!}
        {!! Form::text('icon_subtitle1_ru', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('icon_subtitle2', 'Icon 2 Sub title:') !!}
        {!! Form::text('icon_subtitle2', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('icon_subtitle2_ru', 'Icon 2 Sub title RUS:') !!}
        {!! Form::text('icon_subtitle2_ru', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('icon_subtitle3', 'Icon 3 Sub title:') !!}
        {!! Form::text('icon_subtitle3', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('icon_subtitle3_ru', 'Icon 3 Sub title RUS:') !!}
        {!! Form::text('icon_subtitle3_ru', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('icon_subtitle4', 'Icon 4 Sub title:') !!}
        {!! Form::text('icon_subtitle4', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-3 ">
        {!! Form::label('icon_subtitle4_ru', 'Icon 4 Sub title RUS:') !!}
        {!! Form::text('icon_subtitle4_ru', null, ['class' => 'form-control']) !!}
    </div>

<div class="row">

</div>
    <!-- Details Ru Field -->
    <div class="form-group col-sm-6 col-lg-6 ">
        {!! Form::label('details_title_ru', 'Details Title (RU):') !!}
        {!! Form::text('details_title_ru', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Details En Field -->
    <div class="form-group col-sm-6 col-lg-6">
        {!! Form::label('details_title_en', 'Details Title (EN):') !!}
        {!! Form::text('details_title_en', null, ['class' => 'form-control']) !!}
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
    <!-- Info Field -->
    <div class="form-group col-sm-3">
        {!! Form::label('info', 'Info:') !!}
        {!! Form::text('info', null, ['class' => 'form-control']) !!}
    </div>
    <!-- Info Field -->
    <div class="form-group col-sm-3">
        {!! Form::label('info_rus', 'Info RU:') !!}
        {!! Form::text('info_rus', null, ['class' => 'form-control']) !!}
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.categories.index') !!}" class="btn btn-default">Cancel</a>
</div>

<script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('details_ru');
    CKEDITOR.replace('details_en');

    function checkCategory(e) {
        if ($('#sub_cat_select').val()) {
            $('#sub_category_fields').show()
        } else {
            $('#sub_category_fields').hide()
        }
    }

    document.addEventListener("DOMContentLoaded", checkCategory);
</script>
