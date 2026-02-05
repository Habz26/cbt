<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Soal - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Edit Soal</h2>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="/admin/soal/{{ $question->id }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Ujian</label>
                        <select name="exam_id" class="form-control" required>
                            @foreach($exams as $e)
                                <option value="{{ $e->id }}" {{ $question->exam_id == $e->id ? 'selected' : '' }}>{{ $e->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipe Soal</label>
                        <select name="type" class="form-control" required>
                            <option value="pg" {{ $question->type == 'pg' ? 'selected' : '' }}>Pilihan Ganda</option>
                            <option value="essay" {{ $question->type == 'essay' ? 'selected' : '' }}>Essay</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Soal</label>
                        <textarea name="question" class="form-control" rows="4" required>{{ $question->question }}</textarea>
                    </div>

                    @if($question->type == 'pg')
                        <div class="mb-3">
                            <label class="form-label">Opsi A</label>
                            <input type="text" name="option_a" class="form-control" value="{{ $question->option_a }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Opsi B</label>
                            <input type="text" name="option_b" class="form-control" value="{{ $question->option_b }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Opsi C</label>
                            <input type="text" name="option_c" class="form-control" value="{{ $question->option_c }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Opsi D</label>
                            <input type="text" name="option_d" class="form-control" value="{{ $question->option_d }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jawaban Benar</label>
                            <select name="correct_answer" class="form-control" required>
                                <option value="A" {{ $question->correct_answer == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ $question->correct_answer == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ $question->correct_answer == 'C' ? 'selected' : '' }}>C</option>
                                <option value="D" {{ $question->correct_answer == 'D' ? 'selected' : '' }}>D</option>
                            </select>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Gambar (Opsional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @if($question->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $question->image) }}" alt="Gambar Soal" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Soal</button>
                        <a href="/admin/soal" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
