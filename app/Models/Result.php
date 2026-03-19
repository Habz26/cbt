<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
protected $fillable = ['user_id','exam_id','score','progress'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'progress' => 'array',
    ];

    public function exam(){
        return $this->belongsTo(Exam::class);
    }
}
