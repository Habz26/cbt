<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Ujian - Admin</title>

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
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(14px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.07);
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
            background: rgba(13, 110, 253, 0.1);
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
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            padding: 15px 20px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card {
            border: none;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .btn,
        .form-control {
            border-radius: 10px;
        }

        .exam-list {
            max-height: 60vh;
            overflow-y: auto;
        }

        h2 {
            font-weight: 600;
            color: #343a40;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-title">CBT Admin</div>

        <a href="/admin"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="/admin/exam" class="active"><i class="fas fa-clipboard-list"></i> Kelola Ujian</a>
        <a href="/admin/soal"><i class="fas fa-file-alt"></i> Kelola Soal</a>
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
            <h5 class="mb-0">Kelola Ujian</h5>
        </div>

        <h2 class="mb-3">Kelola Ujian</h2>

        <div class="card p-4 mb-4">
            <form method="POST" action="/admin/exam">
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
                    <label>Nama Ujian:</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>

                <div class="form-group">
                    <label>Durasi (menit):</label>
                    <input type="number" name="duration" class="form-control" value="{{ old('duration') }}"
                        min="10" max="360" required>
                </div>

                <div class="form-group">
                    <label>Waktu Mulai:</label>
                    <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time') }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Waktu Selesai:</label>
                    <input type="datetime-local" name="end_time" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Tambah Ujian</button>
            </form>
        </div>

        <div class="row exam-list">
            @foreach ($exams as $e)
                <div class="col-md-6">
                    <div class="card mb-3 p-3">
                        <h5>{{ $e->title }}</h5>
                        <h6>Exam ID: {{ $e->id }}</h6>
                        <p>Durasi: {{ $e->duration }} menit</p>
                        <p>Mulai: {{ $e->start_time }}</p>
                        <p>Selesai: {{ $e->end_time }}</p>

                        <div class="d-flex gap-2">
                            <a href="/admin/exam/{{ $e->id }}/edit" class="btn btn-warning btn-sm">Edit</a>

                            <form method="POST" action="/admin/exam/{{ $e->id }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus ujian ini?')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
