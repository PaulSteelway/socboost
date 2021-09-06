@hasSection ('keywords')
    @php
        $keywords = $__env->yieldContent('keywords');
        // Тайтл пустой
        if (iconv_strlen($keywords) < 2) {
          $keywords = '';
        }
    @endphp
    <meta property="keywords" content="{{ $keywords }}"/>
@endif
