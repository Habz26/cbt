<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionImport;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'exams' => Exam::count(),
            'questions' => Question::count(),
        ]);
    }

    public function soal()
    {
        return view('admin.soal', [
            'questions' => Question::with('exam')->get(),
            'exams' => Exam::all(),
        ]);
    }

    public function storeSoal(Request $r)
    {
        if ($r->hasFile('excel_file')) {
            // Import from Excel
            Excel::import(new QuestionImport, $r->file('excel_file'));
            return back()->with('success', 'Soal berhasil diimpor dari Excel');
        } else {
            // Manual input
            $data = [
                'exam_id' => $r->exam_id,
                'type' => $r->type,
                'question' => $r->question,
                'option_a' => $r->option_a,
                'option_b' => $r->option_b,
                'option_c' => $r->option_c,
                'option_d' => $r->option_d,
                'correct_answer' => $r->type == 'essay' ? '-' : $r->correct_answer,
            ];

            if ($r->hasFile('image')) {
                $imagePath = $r->file('image')->store('questions', 'public');
                $data['image'] = $imagePath;
            }

            Question::create($data);

            return back();
        }
    }

    public function editSoal($id)
    {
        $question = Question::findOrFail($id);
        $exams = Exam::all();
        return view('admin.edit_soal', compact('question', 'exams'));
    }

    public function updateSoal(Request $r, $id)
    {
        $question = Question::findOrFail($id);

        $question->update([
            'exam_id' => $r->exam_id,
            'type' => $r->type,
            'question' => $r->question,
            'option_a' => $r->option_a,
            'option_b' => $r->option_b,
            'option_c' => $r->option_c,
            'option_d' => $r->option_d,
            'correct_answer' => $r->type == 'essay' ? '-' : $r->correct_answer,
        ]);

        return redirect('/admin/soal')->with('success', 'Soal berhasil diupdate');
    }

    public function deleteSoal($id)
    {
        Question::destroy($id);
        return back();
    }

    public function exam()
    {
        $exams = Exam::all();
        return view('admin.exam', compact('exams'));
    }
    public function storeExam(Request $r)
    {
        Exam::create([
            'title' => $r->title,
            'duration' => $r->duration,
            'start_time' => $r->start_time,
            'end_time' => $r->end_time,
        ]);

        return back();
    }

    public function editExam($id)
    {
        $exam = Exam::findOrFail($id);
        return view('admin.edit_exam', compact('exam'));
    }

    public function updateExam(Request $r, $id)
    {
        $exam = Exam::findOrFail($id);

        $exam->update([
            'title' => $r->title,
            'duration' => $r->duration,
            'start_time' => $r->start_time,
            'end_time' => $r->end_time,
        ]);

        return redirect('/admin/exam')->with('success', 'Ujian berhasil diupdate');
    }

    public function deleteExam($id)
    {
        Exam::destroy($id);
        return back();
    }

    public function results()
    {
        $results = \App\Models\Result::with(['user', 'exam'])->get();

        // Add answers to each result
        foreach ($results as $result) {
            $examQuestions = $result->exam->questions->sortBy('id')->pluck('id')->toArray();
            $answers = \App\Models\Answer::where('user_id', $result->user_id)
                ->whereHas('question', function($q) use ($result) {
                    $q->where('exam_id', $result->exam_id);
                })
                ->with('question')
                ->get()
                ->unique('question_id')
                ->sortBy(function($answer) use ($examQuestions) {
                    return array_search($answer->question_id, $examQuestions);
                })
                ->values();

            $result->answers = $answers;
        }

        return view('admin.results', compact('results'));
    }
}
