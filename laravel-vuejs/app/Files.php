<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    public function getMessage()
    {
        return $this->belongsTo('App\HomeMessage', 'message_id');
    }
}
