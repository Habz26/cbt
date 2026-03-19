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
        $exam = Exam::with('questions')->findOrFail($id);

        if (now() < $exam->start_time) {
            return redirect('/siswa')->with('error', 'Ujian belum dimulai.');
        }

        if (now() > $exam->end_time) {
            return redirect('/siswa')->with('error', 'Ujian sudah berakhir.');
        }

        // Ensure single Result per user-exam (firstOrCreate)
        $result = Result::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'exam_id' => $id,
            ],
            ['progress' => []]
        );

        if ($result->score !== null) {
            return redirect('/siswa')->with('error', 'Anda sudah menyelesaikan ujian ini.');
        }

        return view('siswa.start', compact('exam', 'result'));
    }

    public function submit(Request $r, $id)
    {
        $r->validate([
            'answers' => 'required|array'
        ]);

        $exam = Exam::findOrFail($id);
        $userId = auth()->id();

        // Get or create the single Result record
        $result = Result::firstOrCreate(
            ['user_id' => $userId, 'exam_id' => $id],
            ['progress' => []]
        );

        if ($result->score !== null) {
            return redirect('/siswa')->with('error', 'Ujian sudah disubmit sebelumnya.');
        }

        $score = 0;
        $pgQuestions = $exam->questions()->where('type', 'pg')->count();


        foreach ($r->answers as $qid => $ans) {
            $question = Question::where('id', $qid)->where('exam_id', $id)->firstOrFail();

            // Upsert Answer (update if exists, create if not)
            Answer::updateOrCreate(
                [
                    'user_id' => $userId,
                    'question_id' => $qid
                ],
                ['answer' => $ans]
            );


            // Score ONLY PG questions (essay manual grading by teacher)
            if ($question->type == 'pg' && $question->correct_answer == $ans) {
                $score++;
            }
            // Essay: 0 auto-points, show in results for manual review

        }

        // Update the existing Result with final score
        $result->update(['score' => $score]);

        return redirect('/siswa/result/' . $id)->with('success', 'Ujian selesai! Skor PG: ' . $score . '/' . $pgQuestions);

    }

    public function result($examId)
    {
        $result = Result::where('user_id', auth()->id())
                        ->where('exam_id', $examId)
                        ->with(['exam.questions.answers' => function ($query) {
                            $query->where('user_id', auth()->id());
                        }])
                        ->firstOrFail();

        return view('siswa.result', compact('result'));
    }

    public function saveProgress(Request $request, $id)
    {
        $request->validate([
            'answers' => 'required|array'
        ]);

        $result = Result::where('user_id', auth()->id())
                        ->where('exam_id', $id)
                        ->firstOrFail();

        $result->progress = $request->answers;
        $result->save();

        return response()->json(['status' => 'success']);
    }
}
