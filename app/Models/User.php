<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'user_id';

    /**
     * Get the health info record associated with the user.
     */
    public function healthRecords()
    {
        return $this->hasOne('App\Models\UserHealthInfo', 'user_id', 'uid');
    }
}
