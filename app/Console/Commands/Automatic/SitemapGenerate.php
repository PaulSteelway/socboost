<?php

namespace App\Console\Commands\Automatic;

use App\Http\Managers\SitemapGenerator\SitemapGeneratorCustom;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Crawler\Crawler;
use Spatie\Sitemap\Tags\Url;

class SitemapGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    protected $excludedUrl = [
        'profile',
        'settings',
        'premium_account',
        'topup',
        'operations',
        'subscriptions',
        'tickets',
        'lang',
    ];

    protected $copies = [];

    /**
     * SitemapGenerate constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->setCategoriesCopies();
    }

    public function setCategoriesCopies()
    {
        $adds = DB::table('categories')
            ->select(['categories.url', 'category_add_pages.id'])
            ->join('category_add_pages', 'category_add_pages.category_id', '=', 'categories.id')
            ->get()
            ->toArray();

        foreach ($adds as $add) {
            $this->copies[$add->url][] = $add->id;
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SitemapGeneratorCustom::create(config('app.url'))
            ->configureCrawler(function (Crawler $crawler) {
                $crawler->setUserAgent('Crawler');
            })
            ->hasCrawled(function (Url $url) {
                if (empty($url->segment(1))) {
                    $url->setPriority(1.0)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY);
                } else {
                    if (in_array($url->segment(1), $this->excludedUrl)) {
                        return;
                    }
                    $url->setPriority(0.9)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY);
                    if ($url->segment(1) == 'c' && !empty($this->copies[$url->segment(2)])) {
                        foreach ($this->copies[$url->segment(2)] as $copy) {
                            $url->addAlternate("/c/{$url->segment(2)}/{$copy}");
                        }
                    }
                }
                return $url;
            })
            ->writeToFile(public_path('sitemap_ru.xml'));

        $rootHost = parse_url(config('app.url'), PHP_URL_HOST);
        $enUrl = str_replace($rootHost, "en.{$rootHost}", config('app.url'));
        SitemapGeneratorCustom::create($enUrl)
            ->configureCrawler(function (Crawler $crawler) {
                $crawler->setUserAgent('Crawler');
            })
            ->hasCrawled(function (Url $url) {
                if (in_array($url->segment(1), $this->excludedUrl)) {
                    return;
                }
                $url->setPriority(0.9)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY);
                if ($url->segment(1) == 'c' && !empty($this->copies[$url->segment(2)])) {
                    foreach ($this->copies[$url->segment(2)] as $copy) {
                        $url->addAlternate("/c/{$url->segment(2)}/{$copy}");
                    }
                }
                return $url;
            })
            ->writeToFile(public_path('sitemap_en.xml'));
    }
}
