<?php

namespace App\Repositories;

use App\Models\UserReferral;
use App\Repositories\BaseRepository;

/**
 * Class UserReferralRepository
 * @package App\Repositories
 * @version February 9, 2020, 2:01 pm UTC
 *
 * @method UserReferral findWithoutFail($id, $columns = ['*'])
 * @method UserReferral find($id, $columns = ['*'])
 * @method UserReferral first($columns = ['*'])
 */
class UserReferralRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'link',
        'referral_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return UserReferral::class;
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
