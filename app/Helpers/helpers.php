<?php

/**
  * Генерируем случайный номер для реферальной системы
  * @return int
  * deprecated
  */
// function generateMyId(): int
// {
//
//   $exist = true;
//
//   while ($exist) {
//     $myId = mt_rand();
//     $exist = \App\Models\User::where('my_id', $myId)->first();
//   }
//   return $myId;
// }








//nit: Daan
// refactor

if (!function_exists('sendSmsTwilio')) {
    /**
     * @param $toNumber
     * @param $message
     * @param $from
     *
     * @return void
     */
    function sendSmsTwilio(string $toNumber, string $message, string $from = null)
    {
        \App\Jobs\SendSmsJob::dispatch($toNumber, $message, $from)->onQueue(getSupervisorName() . '-low');
    }
}

/**
 * @param string $currencyId
 * @param $value
 * @return float
 * @throws Exception
 */
function currencyPrecision(string $currencyId, $value): float
{
  if (empty($value)) $value = 0;

  $precision = cache()
    ->tags(['currency', 'precision'])
    ->rememberForever('precision_' . $currencyId, function () use ($currencyId) {
      /** @var \App\Models\Currency $currency */
      $currency = \App\Models\Currency::find($currencyId);

      return $currency->precision > 0
      ? $currency->precision
      : 2;
    });

  return round($value, $precision);
}

/**
 * @param array $keys
 * @return void
 * @throws Exception
 */
function clearCacheByArray(array $keys)
{
    foreach ($keys as $key) {
        cache()->forget($key);
    }
}

/**
 * @param array $tags
 * @return void
 * @throws Exception
 */
function clearCacheByTags(array $tags)
{
  cache()->tags($tags)->flush();
}

/**
 * @return array
 * @throws Exception
 */
function getTransactionTypes(): array
{
    return cache()->remember('h.transactionTypes', getCacheHLifetime('transactionTypes'), function () {
        return \App\Models\TransactionType::get()->toArray();
    });
}



/**
 * @param string $baseCurrency
 * @return array
 * @throws Exception
 *
 * TODO: currency rates have to be as module
 */
function currenciesRates(string $baseCurrency = 'USD'): array
{
    return cache()->tags(['currency', 'precision', 'rates'])->remember('rates_' . $baseCurrency, getCacheHLifetime('currenciesRates'), function () use ($baseCurrency) {
        $rates = \App\Models\Currency::balances();
        array_pull($rates, $baseCurrency);
        $keys = implode(",", array_keys($rates));

        try {
            $f = fopen('https://openexchangerates.org/api/latest.json?app_id=' . env('OPENEXCHANGERATES_API') . '&base=' . $baseCurrency . '&symbols=' . $keys . '&show_alternative=true', 'rb');

            if ($f) {
                $out = "";

                while (!feof($f)) {
                    $out .= fgets($f);
                }

                fclose($f);
                $out = json_decode($out, true);

                if (isset($out['rates'])) {
                    foreach ($out['rates'] as $key => $value) {
                        $rates[$key] = currencyPrecision($baseCurrency, (1 / $value));
                    }
                }
            }
        } finally {
            return $rates;
        }
    });
}

/**
 * @param string|null $key
 * @param string|null $section
 * @return \Carbon\Carbon
 * @throws Exception
 */
function getCacheLifetime($key = null, $section = null)
{
    if (null == $key) {
        throw new Exception('Cache key is empty');
    }

    if (null == $section) {
        throw new Exception('Cache section is empty');
    }

    return now()->addMinutes(config()->get('cache.lifetimes.' . $section . '.' . $key));
}

/**
 * @param null $key
 * @return \Carbon\Carbon
 * @throws Exception
 */
function getCacheILifetime($key = null)
{
    return getCacheLifetime($key, 'i');
}

/**
 * @param null $key
 * @return \Carbon\Carbon
 * @throws Exception
 */
function getCacheALifetime($key = null)
{
    return getCacheLifetime($key, 'a');
}

/**
 * @param null $key
 * @return \Carbon\Carbon
 * @throws Exception
 */
function getCacheHLifetime($key = null)
{
    return getCacheLifetime($key, 'h');
}

/**
 * @param null $key
 * @return \Carbon\Carbon
 * @throws Exception
 */
function getCacheCLifetime($key = null)
{
    return getCacheLifetime($key, 'c');
}

/**
 * @return string
 */
function getTodayLicenceFile()
{
    $today = now()->toDateString();
    $todayTimestamp = strtotime($today);
    $indicatorFile = $todayTimestamp . '.licence';

    return $indicatorFile;
}

/**
 * @return boolean
 */
function checkLicence()
{
    $disk = 'licences';
    $licenceFile = getTodayLicenceFile();

    if (isset($_SERVER['HTTP_HOST']) && preg_match('/\.(test|develop)/', $_SERVER['HTTP_HOST'])) {
        return true;
    }
    return true;

#    return \Illuminate\Support\Facades\Storage::disk($disk)->exists($licenceFile);
}

/**
 * @return string
 */
function getSupervisorName()
{
    return preg_replace('/ /', '-', env('APP_NAME', 'supervisor-1'));
}

/**
 * @return mixed
 */
function getCodeCountryList()
{
    if (app()->getLocale() == 'en') {
        return config('countries')['en'];
    } else {
        return config('countries')['ru'];
    }
}


function getPointsName($qty)
{
    if ($qty == 1) {
        return 'Балл';

    } elseif ($qty > 1 && $qty < 10) {
        return 'Балла';
    } else {
        return 'Баллов';
    }

}


function  isFreePromotionSite()
{
    return request()->getHost() === config('app.free_url') || request()->getHost() === config('app.free_url_en');
}
