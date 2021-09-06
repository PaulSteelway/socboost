<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class News
 * @package App\Models
 *
 * @property string id
 * @property string img
 * @property string slug
 * @property string created_at
 */
class News extends Model
{
    use Uuids;

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var array $fillable */
    protected $fillable = [
        'img',
        'slug',
        'created_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function child() {
        return $this->hasMany(NewsLang::class, 'news_id', 'id');
    }

    /**
     * @param $file
     */
    public function addImg($file) {
        $folder          = 'news_img';
        $destinationPath = public_path() . '/'.$folder.'/';

        if (!empty($this->img)) {
            @unlink($destinationPath . $this->img);
        }

        $ext      = $file->getClientOriginalExtension() ?: 'png';
        $filename = str_random(20) . '.' . $ext;

        $file->move($destinationPath, $filename);
        \Image::make($destinationPath . $filename)->widen(1000)
            ->save($destinationPath . $filename, 70);

        $this->img = $filename;
        $this->save();
    }
}
