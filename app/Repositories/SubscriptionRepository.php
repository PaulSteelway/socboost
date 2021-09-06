<?php

namespace App\Repositories;

use App\Models\Subscription;
use App\Repositories\BaseRepository;

/**
 * Class SubscriptionRepository
 * @package App\Repositories
 * @version April 22, 2020, 6:13 pm UTC
 *
 * @method Subscription findWithoutFail($id, $columns = ['*'])
 * @method Subscription find($id, $columns = ['*'])
 * @method Subscription first($columns = ['*'])
 */
class SubscriptionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'currency_id',
        'type',
        'period',
        'date_at',
        'packet_id',
        'subscription_id',
        'status',
        'payment_method',
        'ip',
        'username',
        'posts',
        'qty_min',
        'qty_max',
        'note'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Subscription::class;
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
