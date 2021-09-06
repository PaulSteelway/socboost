<?= '<' . '?' . 'xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($tags as $tag)
    <url>
    @if (!empty($tag->url))
    <loc>{{ url($tag->url) }}</loc>
    @endif
    @if (!empty($tag->changeFrequency))
<changefreq>{{ $tag->changeFrequency }}</changefreq>
    @endif
    @if (!empty($tag->priority))
<priority>{{ number_format($tag->priority, 1) }}</priority>
    @endif
</url>
@if (count($tag->alternates))
@foreach ($tag->alternates as $alternate)
    <url>
    @if (!empty($tag->url))
    <loc>{{ url($alternate->url) }}</loc>
    @endif
    @if (!empty($tag->changeFrequency))
<changefreq>{{ $tag->changeFrequency }}</changefreq>
    @endif
    @if (!empty($tag->priority))
<priority>{{ number_format($tag->priority, 1) }}</priority>
    @endif
</url>
@endforeach
@endif
@endforeach
</urlset>
