<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Ujian - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { margin: 20px; }
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
        }
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }
                .navbar-nav .nav-item {
            margin: 0 5px;
        }
        .nav-link.active {
            background-color: #007bff;
            border-radius: 5px;
        }
        .exam-card {
            margin-bottom: 15px;
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
                        <a class="nav-link" href="/admin/exam">Kelola Ujian</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/users">Kelola User</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin/schedule">Jadwal Ujian</a>
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
        <h2 class="mt-4">Jadwal Ujian</h2>

        <div class="row">
            @foreach($exams as $exam)
                <div class="col-md-6 exam-card">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $exam->title }}</h5>
                            <p class="card-text">
                                <strong>Durasi:</strong> {{ $exam->duration }} menit<br>
                                <strong>Mulai:</strong> {{ $exam->start_time }}<br>
                                <strong>Selesai:</strong> {{ $exam->end_time }}
                            </p>
                            <a href="/admin/exam/{{ $exam->id }}/edit" class="btn btn-primary">Edit Jadwal</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>