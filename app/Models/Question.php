<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'exam_id', 'type', 'question',
        'option_a', 'option_b', 'option_c', 'option_d', 'option_e',
        'option_a_image', 'option_b_image', 'option_c_image', 'option_d_image', 'option_e_image',
        'correct_answer', 'image'
    ];

    // RELASI ke Exam
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
