<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use App\Models\User;
use App\Models\Result;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionImport;
use App\Imports\UserImport;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $kelas = $request->kelas;
        
        $examCount = Exam::count();
        $questionCount = Question::count();
        
        $userQuery = User::query();
        if ($kelas) {
            $userQuery->where('kelas', $kelas);
        }
        $userCount = $userQuery->count();
        
        $resultQuery = Result::query();
        if ($kelas) {
            $resultQuery->whereHas('user', function ($q) use ($kelas) {
                $q->where('kelas', $kelas);
            });
        }
        $resultCount = $resultQuery->count();

        $examStats = Exam::withCount('questions')->get();
        
        $userStatsQuery = User::selectRaw('kelas, count(*) as count')->whereNotNull('kelas');
        if ($kelas) {
            $userStatsQuery->where('kelas', $kelas);
        }
        $userStats = $userStatsQuery->groupBy('kelas')->get();

        $examsQuery = Exam::whereHas('results', function ($q) use ($kelas) {
            $q->whereNotNull('score');
            if ($kelas) {
                $q->whereHas('user', function ($uq) use ($kelas) {
                    $uq->where('kelas', $kelas);
                });
            }
        });
        $examsWithResults = $examsQuery->take(2)->get();

        $examAnalytics = [];
        $examsForChart = [];

        foreach ($examsWithResults as $index => $exam) {
            $examResultsQuery = Result::with(['user', 'exam.questions'])
                ->where('exam_id', $exam->id)
                ->whereNotNull('score');
            if ($kelas) {
                $examResultsQuery->whereHas('user', function ($q) use ($kelas) {
                    $q->where('kelas', $kelas);
                });
            }
            $examResults = $examResultsQuery->get()->groupBy('user_id');

            $analytics = [];
            $studentNames = [];
            $averageScores = [];
            $totalCorrect = [];

            foreach ($examResults as $userId => $results) {
                $user = $results->first()->user;
                $totalScore = $results->sum('score');
                $totalQuestions = $exam->questions->count();
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

            $examsForChart[] = [
                'id' => $exam->id,
                'title' => $exam->title,
                'studentNames' => $studentNames,
                'averageScores' => $averageScores,
                'totalCorrect' => $totalCorrect
            ];
        }

        $studentAnalytics = [];
        $studentResultsQuery = Result::with(['user', 'exam.questions']);
        if ($kelas) {
            $studentResultsQuery->whereHas('user', function ($q) use ($kelas) {
                $q->where('kelas', $kelas);
            });
        }
        $studentResults = $studentResultsQuery->get()->groupBy('user_id');

        foreach ($studentResults as $userId => $results) {
            $user = $results->first()->user;
            $totalExams = $results->count();
            $totalScore = $results->sum('score');
            $totalUniqueQuestionsAnswered = Answer::where('user_id', $userId)->distinct('question_id')->count('question_id');
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
            'examsForChart',
            'kelas'
        ));
    }

    public function results(Request $request)
    {
        $kelas = $request->kelas;
        $exam_id = $request->exam_id;
        
$allExams = Exam::orderBy('title')->get();
        $allStudentsQuery = User::where('role', 'student');
        if ($kelas) {
            $allStudentsQuery->where('kelas', $kelas);
        }
        $allStudents = $allStudentsQuery->get();
        
$examsQuery = Exam::query();
        
        if ($exam_id) {
            $examsQuery->where('id', $exam_id);
        }
        
        $exams = $examsQuery->with('questions')->get();
        
        $examResults = [];

        foreach ($exams as $exam) {
            $questions = $exam->questions->sortBy('id')->values();
            $pgQuestions = $questions->where('type', 'pg');
            $essayQuestions = $questions->where('type', 'essay');
            $pgCount = $pgQuestions->count();

$resultsQuery = Result::with(['user'])
                ->where('exam_id', $exam->id);
                
            if ($kelas) {
                $resultsQuery->whereHas('user', function ($q) use ($kelas) {
                    $q->where('kelas', $kelas);
                });
            }
            
            $results = $resultsQuery->get()->keyBy('user_id');

            $pgStudents = [];
            $essayStudents = [];

            foreach ($allStudents as $student) {
                $userId = $student->id;
                $result = $results[$userId] ?? null;
                $score = $result ? $result->score : '-';
                $result_id = $result ? $result->id : null;

                $pgAnswers = Answer::where('user_id', $userId)
                    ->whereHas('question', function ($q) use ($exam) {
                        $q->where('exam_id', $exam->id)->where('type', 'pg');
                    })
                    ->get()
                    ->keyBy('question_id');

                $pgAnswerArray = [];
                foreach ($pgQuestions as $q) {
                    $pgAnswerArray[$q->id] = $pgAnswers[$q->id]->answer ?? '-';
                }

                $essayAnswers = Answer::where('user_id', $userId)
                    ->whereHas('question', function ($q) use ($exam) {
                        $q->where('exam_id', $exam->id)->where('type', 'essay');
                    })
                    ->with('question')
                    ->get();

                $pgStudents[] = [
                    'user' => $student,
                    'score' => $score,
                    'pgAnswers' => $pgAnswerArray,
                    'result_id' => $result_id
                ];

                $essayStudents[] = [
                    'user' => $student,
                    'score' => $score,
                    'essayAnswers' => $essayAnswers,
                    'result_id' => $result_id
                ];
            }

            $examResults[] = [
                'exam' => $exam,
                'pgQuestions' => $pgQuestions,
                'essayQuestions' => $essayQuestions,
                'pgStudents' => $pgStudents,
                'essayStudents' => $essayStudents,
                'pgCount' => $pgCount
            ];
        }

        $examsList = $allExams;
        return view('admin.results', compact('examResults', 'allStudents', 'kelas', 'examsList', 'exam_id'));
    }

    public function deleteResult($resultId)
    {
        $result = Result::findOrFail($resultId);
        $result->delete();
        return back()->with('success', 'Data hasil siswa berhasil dihapus dari database.');
    }

    public function resetResult($resultId)
    {
        $result = Result::findOrFail($resultId);
        $result->update(['score' => null, 'progress' => []]);
        return back()->with('success', 'Hasil ujian siswa berhasil direset. Siswa dapat mengerjakan ulang.');
    }

public function users()
    {
        $users = User::withTrashed()
                     ->orderBy('role')
                     ->orderBy('kelas')
                     ->orderBy('name')
                     ->get();
        return view('admin.users', compact('users'));
    }

    public function resetSession($id)
    {
        $user = User::findOrFail($id);
        $oldSessionId = $user->current_session_id;
        $user->current_session_id = null;
        $user->save();

        // Delete session file to prevent reuse
        if ($oldSessionId) {
            $sessionPath = storage_path('framework/sessions/' . $oldSessionId);
            if (file_exists($sessionPath)) {
                unlink($sessionPath);
            }
        }

        // User will be logged out on next request via CheckActiveSession middleware
        return back()->with('success', 'Sesi user ' . $user->name . ' berhasil direset. User akan logout otomatis pada akses berikutnya.');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,student',
            'kelas' => 'nullable|string|max:20',
        ]);

        $userData = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'kelas' => $request->kelas,
        ];

        if (Schema::hasColumn('users', 'plain_password')) {
            $userData['plain_password'] = $request->password;
        }

        User::create($userData);

        return redirect('/admin/users')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Kelola Soal - Index
     */
public function soal()
    {
        $exams = Exam::with(['questions' => function($query) {
            $query->orderBy('id');
        }])->get();
        
        $exams->each(function ($exam) {
            $exam->questions->values()->each(function ($question, $index) {
                $question->number = $index + 1;
            });
        });
        
        return view('admin.soal', compact('exams'));
    }

    /**
     * Store Soal (single + import)
     */
    public function storeSoal(Request $request)
    {
        if ($request->hasFile('excel_file')) {
            // Import Excel
            $request->validate([
                'import_exam_id' => 'required|exists:exams,id',
                'excel_file' => 'required|file|mimes:xlsx,xls|max:2048'
            ]);

            Excel::import(new QuestionImport($request->import_exam_id), $request->file('excel_file'));

            return back()->with('success', 'Soal berhasil diimport dari Excel!');
        } else {
            // Single soal
            $request->validate([
                'exam_id' => 'required|exists:exams,id',
                'type' => 'required|in:pg,essay',
                'question' => 'required|string|max:1000',
                'image' => 'nullable|image|max:2048',
                'option_a' => 'required_if:type,pg',
                'option_b' => 'required_if:type,pg',
                'option_c' => 'required_if:type,pg',
                'option_d' => 'required_if:type,pg',
                'correct_answer' => 'required_if:type,pg|in:A,B,C,D,E'
            ]);

            $data = $request->only(['exam_id', 'type', 'question', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'correct_answer']);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('questions', 'public');
            }

            // Option images
            $optionFields = ['option_a_image', 'option_b_image', 'option_c_image', 'option_d_image', 'option_e_image'];
            foreach ($optionFields as $field) {
                if ($request->hasFile($field)) {
                    $data[$field] = $request->file($field)->store('options', 'public');
                }
            }

            Question::create($data);

            return back()->with('success', 'Soal berhasil ditambahkan!');
        }
    }

    public function editSoal($id)
    {
        $question = Question::findOrFail($id);
        $exams = Exam::all();
        return view('admin.edit_soal', compact('question', 'exams'));
    }

    public function updateSoal(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'type' => 'required|in:pg,essay',
            'question' => 'required|string|max:1000',
            'image' => 'nullable|image|max:2048',
            'option_a' => 'required_if:type,pg',
            'option_b' => 'required_if:type,pg',
            'option_c' => 'required_if:type,pg',
            'option_d' => 'required_if:type,pg',
            'correct_answer' => 'required_if:type,pg|in:A,B,C,D,E'
        ]);

        $data = $request->only(['exam_id', 'type', 'question', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'correct_answer']);

        if ($request->hasFile('image')) {
            // Delete old
            if ($question->image) {
                \Storage::disk('public')->delete($question->image);
            }
            $data['image'] = $request->file('image')->store('questions', 'public');
        }

        $optionFields = ['option_a_image', 'option_b_image', 'option_c_image', 'option_d_image', 'option_e_image'];
        foreach ($optionFields as $field) {
            if ($request->hasFile($field)) {
                if ($question->$field) {
                    \Storage::disk('public')->delete($question->$field);
                }
                $data[$field] = $request->file($field)->store('options', 'public');
            }
        }

        $question->update($data);

        return redirect('/admin/soal')->with('success', 'Soal berhasil diupdate!');
    }

    public function deleteSoal($id)
    {
        $question = Question::findOrFail($id);
        
        // Delete images
        if ($question->image) \Storage::disk('public')->delete($question->image);
        $optionFields = ['option_a_image', 'option_b_image', 'option_c_image', 'option_d_image', 'option_e_image'];
        foreach ($optionFields as $field) {
            if ($question->$field) \Storage::disk('public')->delete($question->$field);
        }

        $question->delete();

        return back()->with('success', 'Soal berhasil dihapus!');
    }

    /**
     * Kelola Ujian - Index
     */
    public function exam()
    {
        $exams = Exam::all();
        return view('admin.exam', compact('exams'));
    }

    /**
     * Store Exam
     */
    public function storeExam(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:exams',
            'duration' => 'required|integer|min:10|max:360',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time'
        ]);

        Exam::create($request->all());

        return back()->with('success', 'Ujian berhasil ditambahkan!');
    }

    public function editExam($id)
    {
        $exam = Exam::findOrFail($id);
        return view('admin.edit_exam', compact('exam'));
    }

    public function updateExam(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255|unique:exams,title,' . $id,
            'duration' => 'required|integer|min:10|max:360',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time'
        ]);

        $exam->update($request->all());

        return redirect('/admin/exam')->with('success', 'Ujian berhasil diupdate!');
    }
    
    public function deleteExam($id)
    {
        $exam = Exam::findOrFail($id);
        // Cascade delete questions/images handled via events or manually if needed
        $exam->questions()->delete(); // Simple delete
        $exam->delete();

        return back()->with('success', 'Ujian dan soal-soalnya berhasil dihapus!');
    }

    /**
     * Jadwal Ujian
     */
    public function schedule()
    {
        $exams = Exam::orderBy('start_time')->get();
        return view('admin.schedule', compact('exams'));
    }

    // Additional users methods (for completeness)
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit_user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,student',
            'kelas' => 'nullable|string|max:20',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6';
        }

        $request->validate($rules);

        $data = $request->only(['name', 'username', 'email', 'role', 'kelas']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
            if (Schema::hasColumn('users', 'plain_password')) {
                $data['plain_password'] = $request->password;
            }
        }

        $user->update($data);

        return redirect('/admin/users')->with('success', 'User berhasil diupdate!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }

public function importUsers(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:2048'
        ]);

        Excel::import(new UserImport, $request->file('file'));

        return redirect('/admin/users')->with('success', 'Users berhasil diimport!');
    }


}