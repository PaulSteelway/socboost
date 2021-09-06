<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class ApiHistory
 * @package App\Models
 * @version July 13, 2020, 10:50 am UTC
 *
 * @property User user
 * @property string user_id
 * @property string action
 * @property string request
 * @property string response
 */
class ApiHistory extends Model
{

    public $table = 'api_history';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'user_id',
        'action',
        'request',
        'response'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'string',
        'action' => 'string',
        'request' => 'string',
        'response' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required|exists:users,id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * @param string $action
     * @return void
     */
    public function setAction($action)
    {
        $this->action = $action;
        $this->save();
    }

    /**
     * @param string $request
     * @return void
     */
    public function setRequest($request)
    {
        $this->request = $request;
        $this->save();
    }

    /**
     * @param string $response
     * @return void
     */
    public function setResponse($response)
    {
        $this->response = $response;
        $this->save();
    }
}
