<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class BlogEntry
 * @package App\Models
 * @version June 1, 2020, 12:56 pm UTC
 *
 * @property string blog
 * @property string|\Carbon\Carbon publish_after
 * @property string slug
 * @property string title
 * @property string author_name
 * @property string author_email
 * @property string author_url
 * @property string image
 * @property string content
 * @property string summary
 * @property string page_title
 * @property string description
 * @property string meta_tags
 * @property boolean display_full_content_in_feed
 */
class BlogCategory extends Model
{

    public $table = 'blog_categories';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'name_ru',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name_ru' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name_ru' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function blog_entries()
    {
        return $this->hasMany(BlogEntry::class);
    }



}
