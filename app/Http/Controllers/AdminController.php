<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionImport;
use App\Imports\UserImport;

class AdminController extends Controller
{
    public function index()
    {
        $examCount = Exam::count();
        $questionCount = Question::count();
        $userCount = User::count();
        $resultCount = \App\Models\Result::count();

        $examStats = Exam::withCount('questions')->get();
        $userStats = User::selectRaw('role, count(*) as count')->groupBy('role')->get();

        // Get unique exams that have results (max 2)
        $examsWithResults = Exam::whereHas('results', function ($q) {
            $q->whereNotNull('score');
        })->take(2)->get();

        // Exam-specific analytics (up to 2 exams)
        $examAnalytics = [];
        
        // For charts - structured data organized by exam
        $examsForChart = [];

        foreach ($examsWithResults as $index => $exam) {
            // Get results for this specific exam
            $examResults = \App\Models\Result::with(['user', 'exam.questions'])
                ->where('exam_id', $exam->id)
                ->whereNotNull('score')
                ->get()
                ->groupBy('user_id');

            $analytics = [];
            $studentNames = [];
            $averageScores = [];
            $totalCorrect = [];

            foreach ($examResults as $userId => $results) {
                $user = $results->first()->user;
                $totalScore = $results->sum('score');
                
                // Get total questions for this exam
                $totalQuestions = $exam->questions->count();
                
                // Calculate average score for this exam
                $averageScore = $totalQuestions > 0 ? round(($totalScore / $totalQuestions) * 100, 1) : 0;

                $analytics[] = [
                    'user' => $user,
                    'examTitle' => $exam->title,
                    'totalScore' => $totalScore,
                    'averageScore' => $averageScore,
                    'totalCorrect' => $totalScore,
                ];
                
                $studentNames[] = $user->name;
                $averageScores[] = $averageScore;
                $totalCorrect[] = $totalScore;
            }

            $examAnalytics[$index] = [
                'exam' => $exam,
                'analytics' => $analytics
            ];

            // Add to chart data structure
            $examsForChart[] = [
                'id' => $exam->id,
                'title' => $exam->title,
                'studentNames' => $studentNames,
                'averageScores' => $averageScores,
                'totalCorrect' => $totalCorrect
            ];
        }

        // Legacy support - keep original variables for backward compatibility
        $studentAnalytics = [];
        $studentResults = \App\Models\Result::with(['user', 'exam.questions'])
            ->get()
            ->groupBy('user_id');

        foreach ($studentResults as $userId => $results) {
            $user = $results->first()->user;
            $totalExams = $results->count();
            $totalScore = $results->sum('score');
            $totalUniqueQuestionsAnswered = \App\Models\Answer::where('user_id', $userId)->distinct('question_id')->count('question_id');
            $averageScore = $totalUniqueQuestionsAnswered > 0 ? round(($totalScore / $totalUniqueQuestionsAnswered) * 100, 1) : 0;

            $studentAnalytics[] = [
                'user' => $user,
                'totalExams' => $totalExams,
                'totalScore' => $totalScore,
                'averageScore' => $averageScore,
                'totalCorrect' => $totalScore,
            ];
        }

        $studentNames = collect($studentAnalytics)->pluck('user.name');
        $studentAverageScores = collect($studentAnalytics)->pluck('averageScore');
        $studentTotalCorrect = collect($studentAnalytics)->pluck('totalCorrect');

        return view('admin.dashboard', compact(
            'examCount', 
            'questionCount', 
            'userCount', 
            'resultCount', 
            'examStats', 
            'userStats', 
            'studentAnalytics', 
            'studentNames', 
            'studentAverageScores', 
            'studentTotalCorrect',
            'examAnalytics',
            'examsForChart'
        ));
    }

    public function soal()
    {
        $questions = Question::with('exam')->get();
        $numberedQuestions = [];
        foreach ($questions->groupBy('exam_id') as $examId => $qs) {
            $number = 1;
            foreach ($qs as $q) {
                $q->number = $number++;
                $numberedQuestions[] = $q;
            }
        }
        return view('admin.soal', [
            'questions' => $numberedQuestions,
            'exams' => Exam::all(),
        ]);
    }

    public function storeSoal(Request $r)
    {
        if ($r->hasFile('excel_file')) {
            // Import from Excel
            $examId = $r->import_exam_id;
            Excel::import(new QuestionImport($examId), $r->file('excel_file'));
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
        $results = \App\Models\Result::with(['user', 'exam'])->whereNotNull('score')->get();

        // Add answers to each result
        foreach ($results as $result) {
            $examQuestions = $result->exam->questions->sortBy('id')->pluck('id')->toArray();
            $answers = \App\Models\Answer::where('user_id', $result->user_id)
                ->whereHas('question', function ($q) use ($result) {
                    $q->where('exam_id', $result->exam_id);
                })
                ->with('question')
                ->get()
                ->unique('question_id')
                ->sortBy(function ($answer) use ($examQuestions) {
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
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,student',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'plain_password' => $request->password,
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
            $user->update([
                'password' => Hash::make($request->password),
                'plain_password' => $request->password,
            ]);
        }

        return redirect('/admin/users')->with('success', 'User berhasil diupdate');
    }

    public function deleteUser($id)
    {
        User::destroy($id);
        return redirect('/admin/users')->with('success', 'User berhasil dihapus');
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new UserImport(), $request->file('excel_file'));

        return redirect('/admin/users')->with('success', 'Users berhasil diimpor dari Excel');
    }



    // Exam Schedule
    public function schedule()
    {
        $exams = Exam::orderBy('start_time')->get();
        return view('admin.schedule', compact('exams'));
    }
}
