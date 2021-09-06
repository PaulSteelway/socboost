@php
  $main = '
{
  "@context": "http://www.schema.org",
  "@type": "ClothingStore",
  "name": "' . trans('site.og_name') . '",
  "alternateName": "' . trans('site.og_name_alter') . '",
  "url": "' . config('app.url') . '",
  "logo": "' . asset('images/logo.png') . '",
  "image": "' . asset('images/og-image.jpg') . '",
  "description": "' . __('site.description') . '",
  "telephone": [
    "' . trans('site.phone1') . '",
    "' . trans('site.phone2') . '",
    "' . trans('site.phone3') . '"
  ],
  "sameAs": [
    "' . trans('site.social1') . '",
    "' . trans('site.social2') . '",
    "' . trans('site.social3') . '"
  ],
  "email": "' . trans('site.email') . '",
  "priceRange": "100-5000UAH",
  "address" : {
    "@type" : "PostalAddress",
    "addressLocality" : "Одесса",
    "addressCountry" : "Украина",
    "postalCode" : "65125"
  },
  "openingHours": "Mo, Tu, We, Th, Fr, Sa, Su 09:00-21:00"
}
';
@endphp


@push('ldjson')
  <script type="application/ld+json" data-seo="ClothingStore">
    {!! preg_replace("#\r|\n|(\s+)#iu", "", $main) !!}
  </script>
@endpush
