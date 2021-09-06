@hasSection ('ldjson')
  <script type="application/ld+json">
    {!! preg_replace("#\r|\n|(\s+)#iu", " ", $__env->yieldContent('ldjson')) !!}
  </script>
@endif
