@extends('layouts.customer')
@php
  // dd($blog_entry);
  $meta_title = __('Blog') . ' - ' . __('site.site');
  $meta_desc = '';

  if (isset($blog_entry->title_ru) && $blog_entry->title_ru) {
    $meta_title = $blog_entry->title_ru . ' - ' . __('site.site');
  }
  if (isset($blog_entry->meta_description) && strip_tags($blog_entry->meta_description)) {
    $meta_desc = strip_tags($blog_entry->meta_description);
  }
  if (isset($blog_entry->meta_description_ru) && strip_tags($blog_entry->meta_description_ru)) {
    $meta_desc = strip_tags($blog_entry->meta_description_ru);
  }
@endphp

@section('title', $meta_title)
@section('description', $meta_desc)

@if (isset($blog_entry->image) && $blog_entry->image)
  @section('og_image', asset($blog_entry->image))
@endif

@section('content')
    <main class="blog_entry" style="padding-top: 100px; min-height: 73vh">
        <div class="container ">
            <hr>
            <div class="blog_entry__header">
                <div class="blog_entry__title">{{$blog_entry->title_ru}}</div>
            </div>
            <div class="blog_entry__image" style="background-image: url('/{{$blog_entry->image}}')"></div>
            <div class="blog_entry__content">{!! $blog_entry->content !!}</div>
            <hr style="margin: 30px">
            <div class="row">
                <div class="col-md-6 blog_entry__social">
                    <div class="blog_entry__social__title">Вы можете поделиться:</div>
                    <div class="blog_entry__social__elements">
                        <div><a target="_blank"
                                href="https://www.facebook.com/sharer/sharer.php?u=https://socialbooster.me/blog_entry/{{$blog_entry->id}}"
                                class="fb-xfbml-parse-ignore"><img src="/images/icons/socials/facebook.png"/></a></div>
                        <div><a target="_blank"
                                href="http://vk.com/share.php?url=https://socialbooster.me/blog_entry/{{$blog_entry->id}}"><img
                                        src="/images/icons/socials/vk.png"/></a></div>
                        <div><a target="_blank"
                                href="http://connect.ok.ru/dk?st.shareUrl=https://socialbooster.me/blog_entry/{{$blog_entry->id}}"><img
                                        src="/images/icons/socials/ok.png"/></a></div>
                        <div><a target="_blank"
                                href="http://twitter.com/share?url=https://socialbooster.me/blog_entry/{{$blog_entry->id}}"><img
                                        src="/images/icons/socials/twitter.png"/></a></div>
                    </div>
                </div>
                <div class="col-md-6 " style="text-align: right; margin-top: 40px">
                    <a href="{{__($blog_entry->link_url)}}" class="app__btn app__btn--prem-prom">{{$blog_entry->link_name ? __($blog_entry->link_name) : __('Get started!')}}</a>
                </div>
            </div>
        </div>
        <div class="blog_entry__other-news">
            <div class="container ">
                @php($blog_category = \App\Models\BlogCategory::find(3))
                <div class="blog-block-title">{{__($blog_category->name_ru)}}</div>
                @include('main_pages.blocks.blog_records', ['category' => $blog_category, 'limit' => 3])
            </div>
        </div>
    </main>
@endsection
