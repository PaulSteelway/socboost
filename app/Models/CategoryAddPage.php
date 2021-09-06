<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class CategoryAddPage
 * @package App\Models
 * @version August 16, 2020, 3:30 pm UTC
 *
 * @property Category $category
 * @property integer $category_id
 * @property string $title
 * @property string $title_ru
 * @property string $title_es
 * @property string $meta_title
 * @property string $meta_title_ru
 * @property string $meta_title_es
 * @property string $meta_keywords
 * @property string $meta_keywords_ru
 * @property string $meta_keywords_es
 * @property string $meta_description
 * @property string $meta_description_ru
 * @property string $meta_description_es
 */
class CategoryAddPage extends Model
{
    public $table = 'category_add_pages';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'category_id',
        'title',
        'title_ru',
        'title_es',
        'meta_title',
        'meta_title_ru',
        'meta_title_es',
        'meta_keywords',
        'meta_keywords_ru',
        'meta_keywords_es',
        'meta_description',
        'meta_description_ru',
        'meta_description_es'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'title' => 'string',
        'title_ru' => 'string',
        'title_es' => 'string',
        'meta_title' => 'string',
        'meta_title_ru' => 'string',
        'meta_title_es' => 'string',
        'meta_keywords' => 'string',
        'meta_keywords_ru' => 'string',
        'meta_keywords_es' => 'string',
        'meta_description' => 'string',
        'meta_description_ru' => 'string',
        'meta_description_es' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'category_id' => 'required|exists:categories,id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
