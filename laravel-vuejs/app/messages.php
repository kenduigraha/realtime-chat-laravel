<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class messages extends Model
{
    public function getFiles()
    {
        return $this->hasOne('App\Files', 'file_id');
    }

    public function getUser()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
