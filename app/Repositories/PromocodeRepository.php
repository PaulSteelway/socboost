<?php

namespace App\Repositories;

use App\Models\Promocode;
use App\Repositories\BaseRepository;

/**
 * Class PromocodeRepository
 * @package App\Repositories
 * @version November 6, 2019, 9:53 am UTC
 *
 * @method Promocode findWithoutFail($id, $columns = ['*'])
 * @method Promocode find($id, $columns = ['*'])
 * @method Promocode first($columns = ['*'])
*/
class PromocodeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'code',
        'reward',
        'data',
        'is_disposable',
        'expires_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Promocode::class;
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
