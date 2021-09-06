<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Repositories\BaseRepository;

/**
 * Class TicketRepository
 * @package App\Repositories
 * @version March 21, 2020, 11:17 am UTC
 *
 * @method Ticket findWithoutFail($id, $columns = ['*'])
 * @method Ticket find($id, $columns = ['*'])
 * @method Ticket first($columns = ['*'])
 */
class TicketRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'subject',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Ticket::class;
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
