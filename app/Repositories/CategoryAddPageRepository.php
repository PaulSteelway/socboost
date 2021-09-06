<?php

namespace App\Repositories;

use App\Models\CategoryAddPage;

/**
 * Class CategoryAddPageRepository
 * @package App\Repositories
 * @version August 16, 2020, 3:30 pm UTC
 */
class CategoryAddPageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CategoryAddPage::class;
    }
}
