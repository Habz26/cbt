<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - CBT UAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Dashboard Admin</h2>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Ujian</h5>
                        <p class="card-text display-4">{{ $exams }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Soal</h5>
                        <p class="card-text display-4">{{ $questions }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="/admin/soal" class="btn btn-primary me-2">Kelola Soal</a>
            <a href="/admin/exam" class="btn btn-secondary me-2">Kelola Ujian</a>
            <a href="/admin/results" class="btn btn-info">Monitoring Hasil</a>
        </div>

        <form method="POST" action="/logout" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</body>
</html>
