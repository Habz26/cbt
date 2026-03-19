<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionImport implements ToModel, WithHeadingRow
{
    protected $examId;

    public function __construct($examId)
    {
        $this->examId = $examId;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $data = [
            'exam_id' => $this->examId,
            'type' => $row['type'],
            'question' => $row['question'],
            'correct_answer' => $row['type'] == 'essay' ? '-' : $row['correct_answer'],
        ];

        // Add options only if present
        $options = ['a', 'b', 'c', 'd', 'e'];
        foreach ($options as $opt) {
            if (isset($row["option_{$opt}"]) && $row["option_{$opt}"] !== '') {
                $data["option_{$opt}"] = $row["option_{$opt}"];
            }
        }

        return new Question($data);

    }
}
