<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Automatic;

use App\Jobs\GenerateDemoForUserJob;
use App\Models\Currency;
use App\Models\Faq;
use App\Models\Language;
use App\Models\News;
use App\Models\NewsLang;
use App\Models\Rate;
use App\Models\Referral;
use App\Models\Reviews;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Console\Command;
use Faker\Factory;

/**
 * Class GenerateDemoDataCommand
 * @package App\Console\Commands\Automatic
 */
class GenerateDemoDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:demo_data {stressLevel?} {onlyUsers?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate demo data for the project';

    /** @var Factory */
    private $faker;

    /** @var int */
    private $stressLevel;

    /** @var int */
    private $onlyUsers;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->faker = Factory::create();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $this->stressLevel = (int) $this->argument('stressLevel');
        $this->onlyUsers = (int) $this->argument('onlyUsers');

        if ($this->stressLevel <= 0) {
            $this->stressLevel = 1;
        }

        if (0 == $this->onlyUsers) {
            $this->comment('Generating news');
            $this->generateNews();
            $this->comment('News generated');

            $this->comment('Generating faqs');
            $this->generateFaqs();
            $this->comment('Faqs generated');

            $this->comment('Generating reviews');
            $this->generateReviews();
            $this->comment('Reviews generated');

            $this->comment('Generating referral levels');
            $this->generateReferralLevels();
            $this->comment('Referral levels generated');

            $this->comment('Generating rates');
            $this->generateRates();
            $this->comment('Rates generated');

            $this->comment('Generating settings');
            $this->generateSettings();
            $this->comment('Settings generated');
        }

        $this->comment('Generating users');
        $this->generateUsers();
        $this->comment('Users generated');
    }

    private function generateNews()
    {
        $languages = Language::select('id')->get();

        if ($languages->count() == 0) {
            $this->warn('Languages not found for NEWS');
            return;
        }

        for ($newsCount = 1; $newsCount <= 15; $newsCount++) {
            $newNews = [
                'slug'       => $this->faker->word . ' ' . $this->faker->word,
                'created_at' => $this->faker->dateTimeThisYear()->format('Y-m-d').' 12:00:00',
            ];

            if (News::where('slug', $newNews['slug'])->count() == 0) {
                $createdNews = News::create($newNews);
                $this->info('news created with slug "' . $newNews['slug'] . '"');
            }

            foreach ($languages as $language) {
                $newsLangs = [
                    'news_id'    => $createdNews->id,
                    'lang_id'    => $language->id,
                    'show'       => $this->faker->numberBetween(0, 1),
                    'title'      => $this->faker->word . ' ' . $this->faker->word . ' ' . $this->faker->word,
                    'teaser'     => $this->faker->text,
                    'text'       => $this->faker->text . ' ' . $this->faker->text,
                    'created_at' => $this->faker->dateTimeThisYear()->format('Y-m-d').' 12:00:00',
                ];

                NewsLang::create($newsLangs);
                $this->info('news with lang ' . $language->code . ' created with title "' . $newsLangs['title'] . '"');
            }
        }
    }

    public function generateFaqs()
    {
        for ($faqsCount = 1; $faqsCount <= 25; $faqsCount++) {
            /** @var Language $lang */
            $lang = Language::select('id')
                ->inRandomOrder()
                ->limit(1)
                ->first();

            if (empty($lang)) {
                $this->warn('Language not found for FAQ');
                return;
            }

            $newFaq = [
                'lang_id'    => $lang->id,
                'title'      => $this->faker->word,
                'text'       => $this->faker->text,
                'created_at' => $this->faker->dateTimeThisYear()->format('Y-m-d').' 12:00:00',
            ];

            Faq::create($newFaq);
            $this->info('FAQ was created with title "' . $newFaq['title'] . '" and lang ' . $lang['code']);
        }
    }

    public function generateReviews()
    {
        for ($reviewsCount = 1; $reviewsCount <= 50; $reviewsCount++) {
            $lang = Language::select('id')
                ->inRandomOrder()
                ->limit(1)
                ->first();

            if (empty($lang)) {
                $this->warn('Language not found for REVIEW');
                return;
            }

            $newReview = [
                'lang_id'    => $lang->id,
                'name'       => $this->faker->name,
                'text'       => $this->faker->text,
                'video'      => $this->faker->boolean ? $this->faker->url : null,
                'created_at' => $this->faker->dateTimeThisYear()->format('Y-m-d').' 12:00:00',
            ];

            Reviews::create($newReview);
            $this->info('customer reviews created with user name ' . $newReview['name']);
        }
    }

    public function generateReferralLevels()
    {
        for ($level = 1; $level <= $this->faker->numberBetween(3, 10); $level++) {
            Referral::create([
                'level'         => $level,
                'percent'       => $this->faker->numberBetween(1, 10),
                'on_load'       => 1,
                'on_profit'     => $this->faker->numberBetween(0, 1),
                'on_task'       => $this->faker->numberBetween(0, 1),
            ]);
            $this->info('level ' . $level . ' registered');
        }
    }

    public function generateRates()
    {
        for ($ratesCount = 1; $ratesCount <= 3; $ratesCount++) {
            $randomCurrency = Currency::select('id')
                ->inRandomOrder()
                ->limit(1)
                ->first();

            if (null === $randomCurrency) {
                $this->warn('can not generate rates, because currencies is not registered.');
                break;
            }

            $min = $this->faker->numberBetween(5, 20);
            $max = $ratesCount * $this->faker->numberBetween(50, 400);

            $newRate = [
                'currency_id' => $randomCurrency->id,
                'name'        => 'plan ' . $this->faker->domainWord,
                'min'         => $ratesCount == 1
                    ? $min
                    : $ratesCount * $min,
                'max'         => $ratesCount == 1
                    ? $max
                    : $ratesCount * $max,
                'daily'       => $this->faker->numberBetween(1, 5),
                'overall'     => $this->faker->numberBetween(0, 50),
                'duration'    => $this->faker->numberBetween(3, 6),
                'payout'      => $this->faker->numberBetween(90, 100),
                'reinvest'    => $this->faker->numberBetween(0, 1),
                'autoclose'   => $this->faker->numberBetween(0, 1),
                'active'      => $ratesCount == 3 ? 0 : 1,
            ];

            Rate::create($newRate);
            $this->info('rate ' . $newRate['name'] . ' registered');
        }
    }

    public function generateSettings()
    {
        Setting::setValue('phone', $this->faker->phoneNumber);
        Setting::setValue('email', $this->faker->email);
        Setting::setValue('telegram', '@'.$this->faker->word);
        Setting::setValue('whatsapp', $this->faker->phoneNumber);
        Setting::setValue('company_name', $this->faker->company);
        Setting::setValue('address', $this->faker->address);
        Setting::setValue('working_time', '09:00 - 18:00 UTC 0');
    }

    public function generateUsers()
    {
        $this->faker = Factory::create();

        for ($usersCount = 1; $usersCount <= $this->faker->numberBetween(50,100)*$this->stressLevel; $usersCount++) {
            $partnerId = User::select('my_id')
                ->inRandomOrder()
                ->limit(1)
                ->first();

            $newUser = [
                'name'       => $this->faker->name,
                'email'      => $this->faker->email,
                'login'      => $this->faker->word . '.' . $this->faker->word,
                'password'   => bcrypt($this->faker->password),
                'partner_id' => !empty($partnerId) ? $partnerId->my_id : null,
                'created_at' => $this->faker->dateTimeThisYear()->format('Y-m-d').' 12:00:00',
            ];

            $checkExists = User::where('login', $newUser['login'])
                ->orWhere('email', $newUser['email'])
                ->get()
                ->count();

            if ($checkExists > 0) {
                continue;
            }

            $user = User::create($newUser);
            GenerateDemoForUserJob::dispatch($user, $this->stressLevel)->onQueue(getSupervisorName().'-low')->delay(0);
            $this->info('user ' . $newUser['name'] . ' registered');
        }
    }
}
