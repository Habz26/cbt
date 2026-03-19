<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Hasil Ujian - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #eef2f7, #f8fafc);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* SIDEBAR (SAMA DASHBOARD) */
        .sidebar {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 240px;
            height: calc(100% - 40px);
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(14px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.07);
            padding: 20px;
            z-index: 1000;
        }

        .sidebar-title {
            font-weight: 600;
            margin-bottom: 25px;
            color: #343a40;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            margin-bottom: 8px;
            border-radius: 10px;
            color: #495057;
            text-decoration: none;
            transition: 0.25s;
        }

        .sidebar a:hover {
            background: rgba(13,110,253,0.1);
            color: #0d6efd;
        }

        .sidebar a.active {
            background: #0d6efd;
            color: #fff;
        }

        .logout {
            position: absolute;
            bottom: 20px;
            width: calc(100% - 40px);
        }

        /* MAIN */
        .main {
            margin-left: 280px;
            padding: 30px;
        }

        /* TOPBAR */
        .topbar {
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(12px);
            padding: 15px 20px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        /* CARD */
        .card {
            border: none;
            border-radius: 18px;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        /* FORM & BUTTON */
        .btn, .form-control, .form-select {
            border-radius: 10px;
        }

        /* TABLE */
        .table-container {
            overflow-x: auto;
        }

        .table {
            font-size: 10px;
            border-collapse: separate;
            border-spacing: 0 6px;
        }

        .table thead th {
            border: none;
            font-size: 9px;
            color: #6c757d;
        }

        .table tbody tr {
            background: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        }

        .table td {
            border-top: none;
            vertical-align: middle;
        }

        .table td:first-child,
        .table th:first-child {
            position: sticky;
            left: 0;
            background: #fff;
            z-index: 2;
            min-width: 120px;
            text-align: left;
        }

        .table td:last-child,
        .table th:last-child {
            position: sticky;
            right: 0;
            background: #fff;
            z-index: 2;
            min-width: 60px;
        }

        .btn-print {
            border-radius: 10px;
        }

        @media print {
            .sidebar,
            .topbar,
            .btn-print {
                display: none !important;
            }

            body {
                background: white;
            }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-title">CBT Admin</div>

    <a href="/admin"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="/admin/soal"><i class="fas fa-file-alt"></i> Kelola Soal</a>
    <a href="/admin/exam"><i class="fas fa-clipboard-list"></i> Kelola Ujian</a>
    <a href="/admin/users"><i class="fas fa-users"></i> Kelola User</a>
    <a href="/admin/schedule"><i class="fas fa-calendar"></i> Jadwal Ujian</a>
    <a href="/admin/results" class="active"><i class="fas fa-chart-bar"></i> Monitoring Hasil</a>

    <form method="POST" action="/logout" class="logout">
        @csrf
        <button class="btn btn-danger w-100">Logout</button>
    </form>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Monitoring Hasil</h5>
            <small class="text-muted">Data hasil ujian siswa</small>
        </div>
        <button class="btn btn-primary btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Print
        </button>
    </div>

    <!-- FILTER -->
    <div class="card p-3 mb-4">
        <form method="GET" action="/admin/results">
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="exam_id" class="form-select">
                        <option value="">Semua Ujian</option>
                        @foreach($examsList as $exam)
                            <option value="{{ $exam->id }}" {{ $exam_id == $exam->id ? 'selected' : '' }}>
                                {{ Str::limit($exam->title, 30) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <select name="kelas" class="form-select">
                        <option value="">Semua Kelas</option>
                        <option value="X DKV" {{ $kelas == 'X DKV' ? 'selected' : '' }}>X DKV</option>
                        <option value="XI DKV" {{ $kelas == 'XI DKV' ? 'selected' : '' }}>XI DKV</option>
                        <option value="XII DKV" {{ $kelas == 'XII DKV' ? 'selected' : '' }}>XII DKV</option>
                        <option value="X PPLG" {{ $kelas == 'X PPLG' ? 'selected' : '' }}>X PPLG</option>
                        <option value="XI PPLG" {{ $kelas == 'XI PPLG' ? 'selected' : '' }}>XI PPLG</option>
                        <option value="XII RPL" {{ $kelas == 'XII RPL' ? 'selected' : '' }}>XII RPL</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary w-100">Filter</button>
                    <a href="/admin/results" class="btn btn-light w-100">Reset</a>
                </div>
            </div>
        </form>
    </div>

    @if (empty($examResults))
        <div class="card p-3">
            <p class="text-muted mb-0">Belum ada hasil ujian.</p>
        </div>
    @else
        @foreach ($examResults as $examResult)
            <div class="card p-4 mb-4">
                <h5>{{ $examResult['exam']->title }}</h5>
                <small class="text-muted">PG: {{ count($examResult['pgQuestions']) }} | Essay: {{ count($examResult['essayQuestions']) }}</small>

                <!-- PG -->
                <div class="mt-4">
                    <h6>PG Results</h6>

                    @php
                        $pgQuestions = $examResult['pgQuestions']->sortBy('id')->values();
                        $pgPerPage = 60;
                        $pgPages = ceil(count($pgQuestions) / $pgPerPage);
                    @endphp

                    @for ($pgPage = 0; $pgPage < $pgPages; $pgPage++)
                        @php
                            $pgStart = $pgPage * $pgPerPage;
                            $pgQuestionsPage = array_slice($pgQuestions->toArray(), $pgStart, $pgPerPage);
                        @endphp

                        <div class="table-container mb-3">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Siswa</th>
                                        @foreach ($pgQuestionsPage as $q)
                                            <th>{{ $loop->index + 1 }}</th>
                                        @endforeach
                                        <th>Skor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($examResult['pgStudents'] as $student)
                                        <tr>
                                            <td>{{ $student['user']->name }}</td>
                                            @foreach ($pgQuestionsPage as $q)
                                                <td>{{ $student['pgAnswers'][$q['id']] ?? '-' }}</td>
                                            @endforeach
                                            <td><strong>{{ $student['score'] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endfor
                </div>

                <!-- ESSAY -->
                <div class="mt-4">
                    <h6>Essay Jawaban</h6>

                    @foreach (collect($examResult['essayQuestions'])->sortBy('id') as $essayQ)
                        <div class="card p-3 mb-3">
                            <strong>Soal {{ $examResult['pgCount'] + $loop->index + 1 }}</strong>
                            <p class="text-muted">{{ Str::limit($essayQ->question, 100) }}</p>

                            <div class="row">
                                @foreach ($examResult['essayStudents'] as $student)
                                    @php
                                        $answer = $student['essayAnswers']->firstWhere('question_id', $essayQ->id);
                                    @endphp

                                    @if ($answer)
                                        <div class="col-md-6 mb-3">
                                            <strong>{{ $student['user']->name }}</strong> ({{ $student['score'] }})

                                            <div class="p-2 bg-light rounded mt-1" style="font-size: 0.85rem;">
                                                {{ Str::limit($answer->answer, 150) }}
                                                <button class="btn btn-sm btn-link p-0"
                                                    onclick="toggleFull('essay-{{ $essayQ->id }}-{{ $student['user']->id }}')">
                                                    Lihat
                                                </button>
                                            </div>

                                            <div id="essay-{{ $essayQ->id }}-{{ $student['user']->id }}" style="display:none;" class="mt-2">
                                                <div class="p-3 bg-white rounded shadow-sm">
                                                    {{ $answer->answer }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif

</div>

<script>
function toggleFull(id) {
    const el = document.getElementById(id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>