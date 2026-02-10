<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionImport;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'exams' => Exam::count(),
            'questions' => Question::count(),
            'users' => \App\Models\User::count(),
            'results' => \App\Models\Result::count(),
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

        $data = [
            'exam_id' => $r->exam_id,
            'type' => $r->type,
            'question' => $r->question,
            'option_a' => $r->option_a,
            'option_b' => $r->option_b,
            'option_c' => $r->option_c,
            'option_d' => $r->option_d,
            'option_e' => $r->option_e,
            'correct_answer' => $r->type == 'essay' ? '-' : $r->correct_answer,
        ];

        // Handle main image
        if ($r->hasFile('image')) {
            $imagePath = $r->file('image')->store('questions', 'public');
            $data['image'] = $imagePath;
        }

        // Handle option images
        $optionImages = ['option_a_image', 'option_b_image', 'option_c_image', 'option_d_image', 'option_e_image'];
        foreach ($optionImages as $optionImage) {
            if ($r->hasFile($optionImage)) {
                $imagePath = $r->file($optionImage)->store('question_options', 'public');
                $data[$optionImage] = $imagePath;
            }
        }

        $question->update($data);

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

    // User Management
    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,student',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/admin/users')->with('success', 'User berhasil ditambahkan');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit_user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,student',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect('/admin/users')->with('success', 'User berhasil diupdate');
    }

    public function deleteUser($id)
    {
        User::destroy($id);
        return redirect('/admin/users')->with('success', 'User berhasil dihapus');
    }

    // Analytics
    public function analytics()
    {
        $examCount = Exam::count();
        $questionCount = Question::count();
        $userCount = User::count();
        $resultCount = \App\Models\Result::count();

        $examStats = Exam::withCount('questions')->get();
        $userStats = User::selectRaw('role, count(*) as count')->groupBy('role')->get();

        return view('admin.analytics', compact('examCount', 'questionCount', 'userCount', 'resultCount', 'examStats', 'userStats'));
    }

    // Exam Schedule
    public function schedule()
    {
        $exams = Exam::orderBy('start_time')->get();
        return view('admin.schedule', compact('exams'));
    }
}
