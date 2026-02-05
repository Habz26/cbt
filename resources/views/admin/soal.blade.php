<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Soal - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { margin: 20px; }
        .form-group { margin-bottom: 15px; }
        .options { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Kelola Soal</h2>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="/admin/soal" id="questionForm" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Ujian:</label>
                        <select name="exam_id" class="form-control" required>
                            @foreach($exams as $e)
                                <option value="{{ $e->id }}">{{ $e->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tipe Soal:</label>
                        <select name="type" class="form-control" id="typeSelect">
                            <option value="pg">Pilihan Ganda</option>
                            <option value="essay">Essay</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Soal:</label>
                        <textarea name="question" class="form-control" placeholder="Soal" rows="3" required></textarea>
                    </div>

                    <div class="options" id="optionsDiv">
                        <div class="form-group">
                            <label>Opsi A:</label>
                            <input type="text" name="option_a" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Opsi B:</label>
                            <input type="text" name="option_b" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Opsi C:</label>
                            <input type="text" name="option_c" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Opsi D:</label>
                            <input type="text" name="option_d" class="form-control">
                        </div>
                    <div class="form-group">
                        <label>Jawaban Benar:</label>
                        <select name="correct_answer" class="form-control">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Gambar (Opsional):</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>

        <hr>

        <div class="row">
            @foreach($questions as $q)
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>{{ $q->question }}</h5>
                            @if($q->image)
                                <img src="{{ asset('storage/' . $q->image) }}" alt="Gambar Soal" class="img-thumbnail mb-2" style="max-width: 200px;">
                            @endif
                            @if($q->type == 'pg')
                                <p>A: {{ $q->option_a }}</p>
                                <p>B: {{ $q->option_b }}</p>
                                <p>C: {{ $q->option_c }}</p>
                                <p>D: {{ $q->option_d }}</p>
                                <p><strong>Jawaban: {{ $q->correct_answer }}</strong></p>
                            @endif
                            <div class="d-flex gap-2">
                                <a href="/admin/soal/{{ $q->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                                <form method="POST" action="/admin/soal/{{ $q->id }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus soal ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <a href="/admin" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>

    <script>
        document.getElementById('typeSelect').addEventListener('change', function() {
            const optionsDiv = document.getElementById('optionsDiv');
            if (this.value === 'pg') {
                optionsDiv.style.display = 'block';
            } else {
                optionsDiv.style.display = 'none';
            }
        });
    </script>
</body>
</html>
