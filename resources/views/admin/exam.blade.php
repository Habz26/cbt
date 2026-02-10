<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Ujian - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { margin: 20px; }
        .form-group { margin-bottom: 15px; }
        .exam-list {
            max-height: 60vh;
            overflow-y: auto;
        }
        .fixed-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .fixed-button:hover {
            background-color: #5a6268;
            color: white;
            text-decoration: none;
        }
        .navbar {
            background: linear-gradient(135deg, #343a40 0%, #495057 100%);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-bottom: 2px solid #007bff;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.25rem;
        }
        .nav-link {
            transition: all 0.3s ease;
            position: relative;
        }
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }
        .nav-link.active {
            background-color: #007bff;
            border-radius: 5px;
        }
        .btn-outline-light:hover {
            background-color: #dc3545;
            border-color: #dc3545;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/admin">CBT SPMB - Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/soal">Kelola Soal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin/exam">Kelola Ujian</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/users">Kelola User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/analytics">Analytics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/schedule">Jadwal Ujian</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/results">Monitoring Hasil</a>
                    </li>
                </ul>
                <form method="POST" action="/logout" class="d-flex">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2 class="mt-4">Kelola Ujian</h2>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="/admin/exam">
                    @csrf

                    <div class="form-group">
                        <label>Nama Ujian:</label>
                        <input type="text" name="title" class="form-control" placeholder="Nama Ujian" required>
                    </div>

                    <div class="form-group">
                        <label>Durasi (menit):</label>
                        <input type="number" name="duration" class="form-control" placeholder="Durasi (menit)" required>
                    </div>

                    <div class="form-group">
                        <label>Waktu Mulai:</label>
                        <input type="datetime-local" name="start_time" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Waktu Selesai:</label>
                        <input type="datetime-local" name="end_time" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Tambah Ujian</button>
                </form>
            </div>
        </div>

        <hr>

        <div class="row exam-list">
            @foreach($exams as $e)
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>{{ $e->title }}</h5>
                            <p>Durasi: {{ $e->duration }} menit</p>
                            <p>Mulai: {{ $e->start_time }}</p>
                            <p>Selesai: {{ $e->end_time }}</p>
                            <a href="/admin/exam/{{ $e->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                            <form method="POST" action="/admin/exam/{{ $e->id }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus ujian ini?')">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


    </div>
</body>
</html>
