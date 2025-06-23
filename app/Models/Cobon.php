<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cobon extends Model
{
     protected $fillable = [
        'number_uses',
        'expire_date',
        'value',
        'cobon',
        'desc',
        'name'
    ];

    public function users() {
        return $this->belongsToMany(User::class,'user_cobon','cobon_id','user_id')->withTimestamps();
    }
}
