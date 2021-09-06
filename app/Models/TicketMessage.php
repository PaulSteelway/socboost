<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class TicketMessage
 * @package App\Models
 * @version March 21, 2020, 10:57 am UTC
 *
 * @property \App\Models\Ticket ticket
 * @property \App\Models\User user
 * @property integer ticket_id
 * @property string user_id
 * @property string message
 */
class TicketMessage extends Model
{
    public $table = 'ticket_messages';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'ticket_id',
        'user_id',
        'message'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ticket_id' => 'integer',
        'user_id' => 'string',
        'message' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ticket_id' => 'required|exists:tickets,id',
        'user_id' => 'required|exists:users,id',
        'message' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ticket()
    {
        return $this->belongsTo(\App\Models\Ticket::class, 'ticket_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
