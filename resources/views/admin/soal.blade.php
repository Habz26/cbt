<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Soal - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #eef2f7, #f8fafc);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

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

        .main {
            margin-left: 280px;
            padding: 30px;
        }

        .topbar {
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(12px);
            padding: 15px 20px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

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

        .form-group {
            margin-bottom: 15px;
        }

        .options {
            display: none;
        }

        .btn, .form-control, .form-select {
            border-radius: 10px;
        }

        .question-list {
            max-height: 60vh;
            overflow-y: auto;
        }

        .table thead {
            background: #f1f3f5;
        }

        h2 {
            font-weight: 600;
            color: #343a40;
        }

        .img-thumbnail {
            border-radius: 10px;
        }
    </style>
</head>

<body>

<div class="sidebar">
    <div class="sidebar-title">CBT Admin</div>

    <a href="/admin"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="/admin/exam"><i class="fas fa-clipboard-list"></i> Kelola Ujian</a>
    <a href="/admin/soal" class="active"><i class="fas fa-file-alt"></i> Kelola Soal</a>
    <a href="/admin/users"><i class="fas fa-users"></i> Kelola User</a>
    <a href="/admin/schedule"><i class="fas fa-calendar"></i> Jadwal Ujian</a>
    <a href="/admin/results"><i class="fas fa-chart-bar"></i> Monitoring Hasil</a>

    <form method="POST" action="/logout" class="logout">
        @csrf
        <button class="btn btn-danger w-100">Logout</button>
    </form>
</div>

<div class="main">

    <div class="topbar d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Kelola Soal</h5>
    </div>

    <h2 class="mb-3">Kelola Soal</h2>

    <div class="card p-4 mb-4">
        <form method="POST" action="/admin/soal" id="questionForm" enctype="multipart/form-data">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="form-group">
                <label>Ujian:</label>
                <select name="exam_id" class="form-select" id="examSelect" required>
                    <option value="">Pilih Ujian</option>
                    @foreach ($exams as $e)
                        <option value="{{ $e->id }}" {{ old('exam_id') == $e->id ? 'selected' : '' }}>
                            {{ $e->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Tipe Soal:</label>
                <select name="type" class="form-select" id="typeSelect">
                    <option value="pg">Pilihan Ganda</option>
                    <option value="essay">Essay</option>
                </select>
            </div>

            <div class="form-group">
                <label>Soal:</label>
                <textarea name="question" class="form-control" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label>Gambar (Opsional):</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="options" id="optionsDiv">
                <div class="form-group">
                    <label>Opsi A:</label>
                    <input type="text" name="option_a" class="form-control">
                    <input type="file" name="option_a_image" class="form-control mt-1">
                </div>
                <div class="form-group">
                    <label>Opsi B:</label>
                    <input type="text" name="option_b" class="form-control">
                    <input type="file" name="option_b_image" class="form-control mt-1">
                </div>
                <div class="form-group">
                    <label>Opsi C:</label>
                    <input type="text" name="option_c" class="form-control">
                    <input type="file" name="option_c_image" class="form-control mt-1">
                </div>
                <div class="form-group">
                    <label>Opsi D:</label>
                    <input type="text" name="option_d" class="form-control">
                    <input type="file" name="option_d_image" class="form-control mt-1">
                </div>
                <div class="form-group">
                    <label>Opsi E:</label>
                    <input type="text" name="option_e" class="form-control">
                    <input type="file" name="option_e_image" class="form-control mt-1">
                </div>
                <div class="form-group">
                    <label>Jawaban Benar:</label>
                    <select name="correct_answer" class="form-select">
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>

        <hr>

        <h5>Import dari Excel</h5>

        <form method="POST" action="/admin/soal" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Ujian:</label>
                <select name="import_exam_id" class="form-select" required>
                    <option value="">Pilih Ujian</option>
                    @foreach ($exams as $e)
                        <option value="{{ $e->id }}">{{ $e->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>File Excel:</label>
                <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls" required>
            </div>

            <button type="submit" class="btn btn-success">Import Soal</button>
        </form>

        <p class="text-muted mt-2">
            Format Excel: type, question, option_a, option_b, option_c, option_d, option_e, correct_answer
        </p>
    </div>

    <div class="row">
@forelse($exams as $exam)
    @foreach($exam->questions as $q)
        <div class="col-md-6 question-card" data-exam-id="{{ $exam->id }}">
            <div class="card mb-3 p-3">
                <h5> {{ $q->number }}. {{ $q->question }}</h5>

                @if ($q->image)
                    <img src="{{ asset('storage/' . $q->image) }}" class="img-thumbnail mb-2">
                @endif

                @if ($q->type == 'pg')
                    <p><strong>A:</strong> {{ $q->option_a ?? '-' }}</p>
                    <p><strong>B:</strong> {{ $q->option_b ?? '-' }}</p>
                    <p><strong>C:</strong> {{ $q->option_c ?? '-' }}</p>
                    <p><strong>D:</strong> {{ $q->option_d ?? '-' }}</p>
                    @if($q->option_e)
                    <p><strong>E:</strong> {{ $q->option_e }}</p>
                    @endif
                    <p><strong>Jawaban: {{ $q->correct_answer }}</strong></p>
                @endif

                <div class="d-flex gap-2">
                    <a href="/admin/soal/{{ $q->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                    <form method="POST" action="/admin/soal/{{ $q->id }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@empty
    <div class="col-12">
        <div class="card p-4 text-center">
            <h5>Belum ada soal</h5>
            <p>Tambahkan ujian terlebih dahulu di menu Kelola Ujian</p>
        </div>
    </div>
@endforelse
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById('typeSelect').addEventListener('change', function() {
    document.getElementById('optionsDiv').style.display =
        this.value === 'pg' ? 'block' : 'none';
});

document.getElementById('examSelect').addEventListener('change', function() {
    const id = this.value;
    const cards = document.querySelectorAll('.question-card');

    cards.forEach(c => {
        c.style.display = (!id || c.dataset.examId === id) ? 'block' : 'none';
    });
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>

</body>
</html>