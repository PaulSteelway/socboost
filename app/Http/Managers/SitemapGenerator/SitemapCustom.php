<?php

namespace App\Http\Managers\SitemapGenerator;

use Spatie\Sitemap\Sitemap;

class SitemapCustom extends Sitemap
{
    /**
     * @return string
     * @throws \Throwable
     */
    public function render(): string
    {
        sort($this->tags);

        $tags = collect($this->tags)->unique('url');

        return view('partials.sitemap_custom')
            ->with(compact('tags'))
            ->render();
    }
}
