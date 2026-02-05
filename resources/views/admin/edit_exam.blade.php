<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ujian - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Edit Ujian</h2>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="/admin/exam/{{ $exam->id }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Ujian</label>
                        <input type="text" name="title" class="form-control" value="{{ $exam->title }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Durasi (menit)</label>
                        <input type="number" name="duration" class="form-control" value="{{ $exam->duration }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Waktu Mulai</label>
                        <input type="datetime-local" name="start_time" class="form-control" value="{{ \Carbon\Carbon::parse($exam->start_time)->format('Y-m-d\TH:i') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Waktu Selesai</label>
                        <input type="datetime-local" name="end_time" class="form-control" value="{{ \Carbon\Carbon::parse($exam->end_time)->format('Y-m-d\TH:i') }}" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Ujian</button>
                        <a href="/admin/exam" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
