<div class="row my-shuffle-container-{{$category->id}}">

    @foreach(\App\Models\BlogEntry::where('category_id', $category->id)->limit($limit?? 9999)->get() as $blog_entry)
        <div class="blog-block-element picture-item-{{$category->id}} column {{$blog_entry->display_full_content_in_feed ? 'blog-block-element--wide': ''}}">
            <a href="/blog_entry/{{$blog_entry->slug}}">
                <div class="blog-block-element__image"
                     style="background-image: url('/{{$blog_entry->image}}')">
                </div>
                <div class="blog-block-element__title">{{$blog_entry->title_ru}}</div>
                @if(strlen($blog_entry->meta_description_ru) > 120)
                    <div class="blog-block-element__text">{!! mb_substr($blog_entry->meta_description_ru, 0, 135) !!}...
                    </div>
                @else
                    <div class="blog-block-element__text">{!! $blog_entry->meta_description_ru !!}</div>
                @endif
                {{-- <div class="blog-block-element__date">{{$blog_entry->created_at->format('d.m.Y')}}</div> --}}
            </a>
        </div>
    @endforeach
    <div class="col-1@sm my-sizer-element-{{$category->id}}"></div>
</div>
