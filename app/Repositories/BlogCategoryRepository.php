<?php

namespace App\Repositories;

use App\Models\BlogCategory;
use App\Models\BlogEntry;
use App\Repositories\BaseRepository;

/**
 * Class BlogCategoryRepository
 * @package App\Repositories
 * @version June 1, 2020, 12:56 pm UTC
 *
 * @method BlogCategory findWithoutFail($id, $columns = ['*'])
 * @method BlogCategory find($id, $columns = ['*'])
 * @method BlogCategory first($columns = ['*'])
*/
class BlogCategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name_ru',

    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return BlogCategory::class;
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
