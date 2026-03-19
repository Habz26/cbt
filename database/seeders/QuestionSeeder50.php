<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Exam;
use App\Models\Question;

class QuestionSeeder50 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first exam or create one
        $exam = Exam::first();
        
        if (!$exam) {
            $exam = Exam::create([
                'title' => 'Ujian Coba 50 Soal',
                'duration' => 120,
                'start_time' => now(),
                'end_time' => now()->addDays(7),
            ]);
        }

        $questions = [];
        
        for ($i = 1; $i <= 50; $i++) {
            $questions[] = [
                'exam_id' => $exam->id,
                'type' => 'pg',
                'question' => 'Ini adalah soal nomor ' . $i . '. Silahkan pilih jawaban yang benar!',
                'option_a' => 'Jawaban A untuk soal ' . $i,
                'option_b' => 'Jawaban B untuk soal ' . $i,
                'option_c' => 'Jawaban C untuk soal ' . $i,
                'option_d' => 'Jawaban D untuk soal ' . $i,
                'option_e' => 'Jawaban E untuk soal ' . $i,
                'correct_answer' => ['A', 'B', 'C', 'D', 'E'][array_rand(['A', 'B', 'C', 'D', 'E'])],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('questions')->insert($questions);
        
        echo "Berhasil menambahkan 50 soal dummy untuk ujian: " . $exam->title . "\n";
    }
}

