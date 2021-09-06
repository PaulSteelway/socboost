<?php

namespace App\Http\Managers\SitemapGenerator;

use Illuminate\Support\Collection;
use Spatie\Crawler\Crawler;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\SitemapIndex;

class SitemapGeneratorCustom extends SitemapGenerator
{
    /**
     * SitemapGeneratorCustom constructor.
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        parent::__construct($crawler);
        $this->sitemaps = new Collection([new SitemapCustom()]);
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function writeToFile(string $path)
    {
        $sitemap = $this->getSitemap();

        if ($this->maximumTagsPerSitemap) {
            $sitemap = SitemapIndex::create();
            $format = str_replace('.xml', '_%d.xml', $path);

            // Parses each sub-sitemaps, writes and pushs them into the sitemap index
            $this->sitemaps->each(function (SitemapCustom $item, int $key) use ($sitemap, $format) {
                $path = sprintf($format, $key);

                $item->writeToFile(sprintf($format, $key));
                $sitemap->add(last(explode('public', $path)));
            });
        }

        $sitemap->writeToFile($path);

        return $this;
    }
}
