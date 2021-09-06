@hasSection ('title')
  @php
    $title = $__env->yieldContent('title');
    $meta_title = $__env->yieldContent('meta_title')?:$title;
    // Тайтл пустой
    if (iconv_strlen($title) < 2) {
      $title = __('site.title');
    }
    if (iconv_strlen($meta_title) < 2) {
      $meta_title = __('site.title');
    }
  @endphp
@else
  @php
    $title = __('site.title');
    $meta_title = __('site.title');
  @endphp
@endif

<title>{{ $title }}</title>
<meta property="title" content="{{ $meta_title }}"/>
<meta property="og:title" content="{{ $meta_title }}"/>
<meta property="vk:title" content="{{ $meta_title }}"/>
<meta property="twitter:title" content="{{ $meta_title }}"/>
<meta itemprop="name" content="{{ $meta_title }}" />
<meta property="og:site_name" content="{{ $meta_title }}">
