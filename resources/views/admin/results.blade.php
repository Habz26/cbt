<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Hasil Ujian - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Monitoring Hasil Ujian</h2>

        @if($results->isEmpty())
            <div class="alert alert-info">
                <p>Belum ada hasil ujian yang tersedia.</p>
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
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
                                        <td>{{ $result->user->name ?? 'N/A' }}</td>
                                        <td>{{ $result->exam->title }}</td>
                                        <td><strong>{{ $result->score }}</strong></td>
                                        <td>{{ $result->created_at->format('d-m-Y H:i') }}</td>
                                        <td>
                                            <table class="table table-sm table-borderless" style="font-size: 0.8em;">
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
                                                            <td>{{ $index + 1 }}. {{ preg_replace('/^\d+\./', ($index + 1) . '.', $answer->question->question) }}</td>
                                                            <td>{{ $answer->answer }}</td>
                                                            <td>{{ $answer->question->correct_answer }}</td>
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

        <div class="mt-4">
            <a href="/admin" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>

        <form method="POST" action="/logout" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</body>
</html>
