<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enroll extends Model
{
    protected $fillable = [
        'user_id', 'topic_id',
    ];
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function topic()
    {
        return $this->belongsTo('App\Models\Topic');
    }
}
