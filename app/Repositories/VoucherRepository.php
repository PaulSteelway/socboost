<?php

namespace App\Repositories;

use App\Models\Voucher;
use App\Repositories\BaseRepository;

/**
 * Class VoucherRepository
 * @package App\Repositories
 * @version March 14, 2020, 6:00 pm UTC
 *
 * @method Voucher findWithoutFail($id, $columns = ['*'])
 * @method Voucher find($id, $columns = ['*'])
 * @method Voucher first($columns = ['*'])
 */
class VoucherRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'code',
        'amount',
        'currency_id',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Voucher::class;
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
