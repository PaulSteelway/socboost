<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

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
class BlogEntry extends Model
{

    // use HasSlug;


    public $table = 'blog_entries';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'blog',
        'publish_after',
        'slug',
        'title_ru',
        'author_name',
        'author_email',
        'author_url',
        'image',
        'content',
        'summary',
        'page_title',
        'description',
        'meta_tags',
        'display_full_content_in_feed',
        'category_id',
        'meta_title',
        'meta_title_ru',
        'meta_description',
        'meta_description_ru',
        'meta_keywords',
        'meta_keywords_ru',
        'link_url',
        'link_name',
        'slug',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'blog' => 'string',
        'publish_after' => 'datetime',
        'slug' => 'string',
        'title' => 'string',
        'author_name' => 'string',
        'author_email' => 'string',
        'author_url' => 'string',
        'image' => 'string',
        'content' => 'string',
        'summary' => 'string',
        'page_title' => 'string',
        'description' => 'string',
        'meta_tags' => 'string',
        'display_full_content_in_feed' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    /**
     * Get the options for generating the slug.
     */
    // public function getSlugOptions() : SlugOptions
    // {
    //     return SlugOptions::create()
    //         ->generateSlugsFrom('title_ru')
    //         ->saveSlugsTo('slug');
    // }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

}
