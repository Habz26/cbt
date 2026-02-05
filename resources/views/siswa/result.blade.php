<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian - CBT UAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Hasil Ujian</h2>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $result->exam->title }}</h5>
                <p class="card-text"><strong>Jawaban sudah disubmit</strong></p>
                <p class="card-text">Tanggal Submit: {{ $result->created_at->format('d-m-Y H:i') }}</p>
            </div>
        </div>

        <div class="mt-4">
            <a href="/siswa" class="btn btn-primary">Kembali ke Dashboard</a>
        </div>

        <form method="POST" action="/logout" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</body>
</html>
