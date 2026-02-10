<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Soal - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { margin: 20px; }
        .form-group { margin-bottom: 15px; }
        .options { display: none; }
        .question-list {
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
            <a class="navbar-brand" href="/admin">CBT UAS - Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin">Dashboard</a>
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
        <h2 class="mt-4">Kelola Soal</h2>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="/admin/soal" id="questionForm" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Ujian:</label>
                        <select name="exam_id" class="form-control" id="examSelect" required>
                            <option value="">Pilih Ujian</option>
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
                            <input type="file" name="option_a_image" class="form-control mt-1" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label>Opsi B:</label>
                            <input type="text" name="option_b" class="form-control">
                            <input type="file" name="option_b_image" class="form-control mt-1" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label>Opsi C:</label>
                            <input type="text" name="option_c" class="form-control">
                            <input type="file" name="option_c_image" class="form-control mt-1" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label>Opsi D:</label>
                            <input type="text" name="option_d" class="form-control">
                            <input type="file" name="option_d_image" class="form-control mt-1" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label>Opsi E:</label>
                            <input type="text" name="option_e" class="form-control">
                            <input type="file" name="option_e_image" class="form-control mt-1" accept="image/*">
                        </div>
                    <div class="form-group">
                        <label>Jawaban Benar:</label>
                        <select name="correct_answer" class="form-control">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Gambar (Opsional):</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                </form>

                <hr>

                <h4>Import dari Excel</h4>
                <form method="POST" action="/admin/soal" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>File Excel:</label>
                        <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls" required>
                    </div>
                    <button type="submit" class="btn btn-success">Import Soal</button>
                </form>
                <p class="text-muted">Format Excel: Kolom harus memiliki header: exam_id, type, question, option_a, option_b, option_c, option_d, option_e, correct_answer</p>
            </div>
        </div>

        <hr>

        <div class="row">
            @foreach($questions as $q)
                <div class="col-md-6 question-card" data-exam-id="{{ $q->exam_id }}">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>{{ $loop->iteration }}. {{ $q->question }}</h5>
                            @if($q->image)
                                <img src="{{ asset('storage/' . $q->image) }}" alt="Gambar Soal" class="img-thumbnail mb-2" style="max-width: 200px;">
                            @endif
                            @if($q->type == 'pg')
                                <p>A: {{ $q->option_a }}</p>
                                <p>B: {{ $q->option_b }}</p>
                                <p>C: {{ $q->option_c }}</p>
                                <p>D: {{ $q->option_d }}</p>
                                @if($q->option_e)
                                    <p>E: {{ $q->option_e }}</p>
                                @endif
                                <p><strong>Jawaban: {{ $q->correct_answer }}</strong></p>
                            @else
                                <p><strong>Jawaban: -</strong></p>
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

        document.getElementById('examSelect').addEventListener('change', function() {
            const selectedExamId = this.value;
            const questionCards = document.querySelectorAll('.question-card');

            questionCards.forEach(card => {
                const cardExamId = card.getAttribute('data-exam-id');
                if (selectedExamId === '' || cardExamId === selectedExamId) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
