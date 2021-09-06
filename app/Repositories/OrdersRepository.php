<?php

namespace App\Repositories;

use App\Models\Order;

/**
 * Class OrdersRepository
 * @package App\Repositories
 * @version July 2, 2020, 6:17 pm UTC
 *
 * @method Order findWithoutFail($id, $columns = ['*'])
 * @method Order find($id, $columns = ['*'])
 * @method Order first($columns = ['*'])
 */
class OrdersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'order_id',
        'name',
        'user_id',
        'link',
        'quantity',
        'price',
        'status',
        'packet_id',
        'jap_id',
        'jap_status',
        'subscription_id',
        'username',
        'min',
        'max',
        'expiry',
        'type',
        'product_id'
    ];

    /**
     * Get searchable fields array
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
        return Order::class;
    }
}
