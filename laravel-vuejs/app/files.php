<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class files extends Model
{
    public function getMessage()
    {
        return $this->belongsTo('App\Messages', 'message_id');
    }
}
