<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Ujian - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { margin: 20px; }
        .form-group { margin-bottom: 15px; }
    </style>
</head>
<body>
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

        <div class="row">
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

        <a href="/admin" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
</body>
</html>
