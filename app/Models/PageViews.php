<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class PageViews
 * @package App\Models
 *
 * @property string user_id
 * @property string page_url
 * @property string user_ip
 */
class PageViews extends Model
{
    /** @var array $fillable */
    protected $fillable = [
        'user_id',
        'page_url',
        'user_ip',
    ];

    /**
     * @return mixed
     */
    public static function addRecord()
    {
        return self::create([
            'user_id'  => \Auth::user()->id ?? NULL,
            'page_url' => url()->full(),
            'user_ip'  => $_SERVER['REMOTE_ADDR']
        ]);
    }
}
