<?php

namespace App\Repositories;

use App\Models\FaqCategory;
use App\Repositories\BaseRepository;

/**
 * Class FaqCategoryRepository
 * @package App\Repositories
 * @version April 10, 2020, 10:54 am UTC
 *
 * @method FaqCategory findWithoutFail($id, $columns = ['*'])
 * @method FaqCategory find($id, $columns = ['*'])
 * @method FaqCategory first($columns = ['*'])
 */
class FaqCategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'status',
        'order',
        'name_ru',
        'name_en',
        'icon'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FaqCategory::class;
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
