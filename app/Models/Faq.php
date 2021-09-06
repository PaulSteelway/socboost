<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class Faq
 * @package App\Models
 * @version April 10, 2020, 1:27 pm UTC
 *
 * @property \App\Models\FaqCategory category
 * @property integer category_id
 * @property integer order
 * @property string question_ru
 * @property string question_en
 * @property string answer_ru
 * @property string answer_en
 */
class Faq extends Model
{
    public $table = 'faqs';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'category_id',
        'order',
        'question_ru',
        'question_en',
        'answer_ru',
        'answer_en'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'order' => 'integer',
        'question_ru' => 'string',
        'question_en' => 'string',
        'answer_ru' => 'string',
        'answer_en' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'category_id' => 'required|exists:faq_categories,id',
        'order' => 'required',
        'question_ru' => 'required|string|max:191',
        'question_en' => 'required|string|max:191',
        'answer_ru' => 'required|string',
        'answer_en' => 'required|string'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'category_id');
    }
}
