<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\BaseRepository;

/**
 * Class ProductRepository
 * @package App\Repositories
 * @version May 19, 2020, 8:43 pm UTC
 *
 * @method Product findWithoutFail($id, $columns = ['*'])
 * @method Product find($id, $columns = ['*'])
 * @method Product first($columns = ['*'])
*/
class ProductsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'account_category_id',
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
        return Product::class;
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
