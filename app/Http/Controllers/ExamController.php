<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Result;


class ExamController extends Controller
{
    public function index()
    {
        // tampilkan ujian yang belum berakhir (aktif atau akan datang)
        $exams = Exam::where('end_time', '>', now())->get();

        return view('siswa.dashboard', compact('exams'));
    }

    public function start($id)
    {
        // ambil exam + soal
        $exam = Exam::with('questions')->findOrFail($id);

        // cek apakah ujian aktif
        if (now() < $exam->start_time) {
            return redirect('/siswa')->with('error', 'Ujian belum dimulai.');
        }

        if (now() > $exam->end_time) {
            return redirect('/siswa')->with('error', 'Ujian sudah berakhir.');
        }

        // cek apakah siswa sudah pernah mengerjakan ujian ini
        $existingResult = Result::where('user_id', auth()->id())
                                ->where('exam_id', $id)
                                ->first();

        if ($existingResult) {
            return redirect('/siswa')->with('error', 'Anda sudah mengerjakan ujian ini sebelumnya.');
        }

        return view('siswa.start', compact('exam'));
    }

    public function submit(Request $r, $id)
    {
        $r->validate([
            'answers'=>'required|array'
        ]);

        $score = 0;
        $totalQuestions = 0;

        foreach($r->answers as $qid=>$ans){
            $q = Question::where('id',$qid)->where('exam_id',$id)->firstOrFail();
            $totalQuestions++;

            Answer::create([
                'user_id'=>auth()->id(),
                'question_id'=>$qid,
                'answer'=>$ans
            ]);

            if($q->type=='pg' && $q->correct_answer==$ans){
                $score++;
            }
        }

        Result::create([
            'user_id'=>auth()->id(),
            'exam_id'=>$id,
            'score'=>$score
        ]);

        return redirect('/siswa/result/'.$id)->with('success','Ujian selesai');
    }

    public function result($examId)
    {
        $result = Result::where('user_id', auth()->id())
                        ->where('exam_id', $examId)
                        ->with('exam')
                        ->firstOrFail();

        return view('siswa.result', compact('result'));
    }
}
