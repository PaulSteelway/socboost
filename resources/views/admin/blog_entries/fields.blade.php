

<div class="form-group col-sm-6">
    {!! Form::label('meta_title_ru', 'Meta Title (RU):') !!}
    {!! Form::text('meta_title_ru', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
   {!! Form::label('meta_description', 'Meta Description (RU):') !!}
   {!! Form::text('meta_description', null, ['class' => 'form-control']) !!}
</div>

{{-- <div class="form-group col-sm-6">
    {!! Form::label('meta_keywords_ru', 'Meta Keywords (RU):') !!}
    {!! Form::text('meta_keywords_ru', null, ['class' => 'form-control']) !!}
</div> --}}


<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title_ru', 'Title:') !!}
    {!! Form::text('title_ru', null, ['class' => 'form-control']) !!}
</div>


<!-- Page Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('page_title', 'Page Title:') !!}
    {!! Form::text('page_title', null, ['class' => 'form-control']) !!}
</div>

<!-- Image Field -->
<div class="form-group col-sm-6 col-lg-6">
    {!! Form::label('image', 'Image:') !!}
    <div style="display: flex">
        {!! Form::file('image', null, ['class' => 'form-control']) !!}
        @if(!empty($blogEntry->image))
            <img src="/{{$blogEntry->image}}" width="24" alt="">
        @endif
    </div>
</div>
<div class="form-group col-sm-3 col-lg-3">
    {!! Form::label('link_url', 'Link url') !!}
    {!! Form::text('link_url', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3 col-lg-3">
    {!! Form::label('link_name', 'Link Name') !!}
    {!! Form::text('link_name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3 col-lg-3">
    {!! Form::label('category_id', 'Category') !!}
    {!! Form::select('category_id', \App\Models\BlogCategory::pluck('name_ru', 'id'),  null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3 col-lg-3">
    {!! Form::label('slug', 'Slug') !!}
    {!! Form::text('slug', null, ['class' => 'form-control']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('meta_description_ru', null, ['id' => 'meta_description_ru', 'class' => 'form-control']) !!}
</div>


<!-- Content Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('content', 'Content:') !!}
    {!! Form::textarea('content', null, ['id' => 'blog_content', 'class' => 'form-control']) !!}
</div>


<!-- Display Full Content In Feed Field -->
<div class="form-group  col-sm-3 col-lg-3" style="padding-left: 20px;">
    {!! Form::label('display_full_content_in_feed', 'Width 60%:') !!}
    {!! Form::checkbox('display_full_content_in_feed', '1', null) !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.blogEntries.index') }}" class="btn btn-default">Cancel</a>
</div>

<script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('meta_description_ru');
    CKEDITOR.replace('blog_content');
</script>
