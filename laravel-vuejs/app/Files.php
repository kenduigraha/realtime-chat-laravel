<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected $fillable = [
        'title', 'file'
    ];

    public function getMessage()
    {
        return $this->belongsTo('App\HomeMessage', 'file_id');
    }
}
