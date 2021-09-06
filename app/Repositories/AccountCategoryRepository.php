<?php

namespace App\Repositories;

use App\Models\AccountCategory;
use App\Repositories\BaseRepository;

/**
 * Class AccountCategoryRepository
 * @package App\Repositories
 * @version May 19, 2020, 8:42 pm UTC
 *
 * @method AccountCategory findWithoutFail($id, $columns = ['*'])
 * @method AccountCategory find($id, $columns = ['*'])
 * @method AccountCategory first($columns = ['*'])
*/
class AccountCategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name_ru',
        'name_en',
        'status',
        'url',
        'details_ru',
        'details_en',
        'icon_class',
        'icon_img',
        'icon_img_active'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AccountCategory::class;
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
