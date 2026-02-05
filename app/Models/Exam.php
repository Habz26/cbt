<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['title','start_time','end_time','duration'];
    public function questions(){ return $this->hasMany(Question::class); }
}
