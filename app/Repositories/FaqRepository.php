<?php

namespace App\Repositories;

use App\Models\Faq;
use App\Repositories\BaseRepository;

/**
 * Class FaqRepository
 * @package App\Repositories
 * @version April 10, 2020, 1:27 pm UTC
 *
 * @method Faq findWithoutFail($id, $columns = ['*'])
 * @method Faq find($id, $columns = ['*'])
 * @method Faq first($columns = ['*'])
 */
class FaqRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'category_id',
        'order',
        'question_ru',
        'question_en',
        'answer_ru',
        'answer_en'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Faq::class;
    }


    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }
}
