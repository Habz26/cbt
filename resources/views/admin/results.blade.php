<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Hasil Ujian - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { margin: 20px 0; }
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
                        <a class="nav-link" href="/admin/exam">Kelola Ujian</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/users">Kelola User</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/schedule">Jadwal Ujian</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin/results">Monitoring Hasil</a>
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
        <h2 class="mt-4">Monitoring Hasil Ujian</h2>

        @if($results->isEmpty())
            <div class="alert alert-info">
                <p>Belum ada hasil ujian yang tersedia.</p>
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                        <table class="table table-striped">
                            <thead style="position: sticky; top: 0; background-color: white; z-index: 10; text-align: center;">
                                <tr>
                                    <th>Nama Siswa</th>
                                    <th>Ujian</th>
                                    <th>Skor</th>
                                    <th>Tanggal Submit</th>
                                    <th>Jawaban</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                    <tr>
                                        <td style="text-align: center;">{{ $result->user->name ?? 'N/A' }}</td>
                                        <td style="text-align: center;">{{ $result->exam->title }}</td>
                                        <td style="text-align: center;"><strong>{{ $result->score }}</strong></td>
                                        <td style="text-align: center;">{{ $result->created_at->format('d-m-Y H:i') }}</td>
                                        <td>
                                            <table class="table table-sm table-bordered" style="font-size: 0.8em;">
                                                <thead>
                                                    <tr>
                                                        <th>Soal</th>
                                                        <th>Jawaban Siswa</th>
                                                        <th>Jawaban Benar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($result->answers as $index => $answer)
                                                        <tr>
                                                            <td>
                                                                {{ $index + 1 }}. {!! nl2br(e($answer->question->question)) !!}
                                                                @if($answer->question->image)
                                                                    <br><img src="{{ asset('storage/' . $answer->question->image) }}" alt="Question Image" class="img-fluid" style="max-width: 200px;">
                                                                @endif
                                                                @if($answer->question->type == 'multiple_choice')
                                                                    <br><strong>Opsi:</strong>
                                                                    <ul style="list-style-type: none; padding-left: 0;">
                                                                        <li>A. {{ $answer->question->option_a }} @if($answer->question->option_a_image)<img src="{{ asset('storage/' . $answer->question->option_a_image) }}" alt="Option A" class="img-fluid" style="max-width: 100px;">@endif</li>
                                                                        <li>B. {{ $answer->question->option_b }} @if($answer->question->option_b_image)<img src="{{ asset('storage/' . $answer->question->option_b_image) }}" alt="Option B" class="img-fluid" style="max-width: 100px;">@endif</li>
                                                                        <li>C. {{ $answer->question->option_c }} @if($answer->question->option_c_image)<img src="{{ asset('storage/' . $answer->question->option_c_image) }}" alt="Option C" class="img-fluid" style="max-width: 100px;">@endif</li>
                                                                        <li>D. {{ $answer->question->option_d }} @if($answer->question->option_d_image)<img src="{{ asset('storage/' . $answer->question->option_d_image) }}" alt="Option D" class="img-fluid" style="max-width: 100px;">@endif</li>
                                                                        @if($answer->question->option_e)
                                                                            <li>E. {{ $answer->question->option_e }} @if($answer->question->option_e_image)<img src="{{ asset('storage/' . $answer->question->option_e_image) }}" alt="Option E" class="img-fluid" style="max-width: 100px;">@endif</li>
                                                                        @endif
                                                                    </ul>
                                                                @endif
                                                            </td>
                                                            <td>{{ $answer->answer }}</td>
                                                            <td>{{ $answer->question->type == 'essay' ? '-' : $answer->question->correct_answer }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif


    </div>
</body>
</html>
