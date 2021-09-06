@extends('layouts.customer')

@section('title', __('Blog') . ' - ' . __('site.site'))

@section('content')

    <main class="blog-page" style="padding-top: 100px; min-height: 73vh">
        <div class="container blog-block ">
            @foreach(\App\Models\BlogCategory::all() as $category )
                <hr>
                <div class="blog-block-title">{{__($category->name_ru)}}</div>
                    @include('main_pages.blocks.blog_records', ['category' => $category])
            @endforeach

        </div>
    </main>

@endsection
