<?php

namespace App\Repositories;

use App\Models\TicketMessage;
use App\Repositories\BaseRepository;

/**
 * Class TicketMessageRepository
 * @package App\Repositories
 * @version March 21, 2020, 1:29 pm UTC
 *
 * @method TicketMessage findWithoutFail($id, $columns = ['*'])
 * @method TicketMessage find($id, $columns = ['*'])
 * @method TicketMessage first($columns = ['*'])
 */
class TicketMessageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ticket_id',
        'user_id',
        'message'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TicketMessage::class;
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
