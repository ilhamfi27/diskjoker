<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBiodata extends Model
{
    protected $table = 'user_biodata';

    protected $fillable = [
        'nickname', 'avatar', 'level', 'user_id'
    ];

    function user()
    {
        return $this->belongsTo('App\User');
    }
}
