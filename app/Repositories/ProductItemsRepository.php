<?php

namespace App\Repositories;

use App\Models\ProductItem;
use App\Repositories\BaseRepository;

/**
 * Class ProductItemRepository
 * @package App\Repositories
 * @version May 19, 2020, 8:43 pm UTC
 *
 * @method ProductItem findWithoutFail($id, $columns = ['*'])
 * @method ProductItem find($id, $columns = ['*'])
 * @method ProductItem first($columns = ['*'])
*/
class ProductItemsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'qty',
        'price',
        'product_id',
        'jap_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductItem::class;
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
