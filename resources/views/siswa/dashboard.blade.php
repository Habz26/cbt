<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - CBT SPMB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-size: 1.1em; }
        .card { margin: 20px 0; }
        .card-title { font-size: 1.3em; }
        .card-text { font-size: 1.1em; }
        .btn { font-size: 1.1em; padding: 10px 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Dashboard Siswa</h2>

        @if($exams->isEmpty())
            <div class="alert alert-info">
                <p>Tidak ada ujian yang tersedia.</p>
            </div>
        @else
            <div class="row">
                @foreach($exams as $exam)
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $exam->title }}</h5>
                                <p class="card-text">Durasi: {{ $exam->duration }} menit</p>
                                <p class="card-text">Waktu: {{ $exam->start_time }} s/d {{ $exam->end_time }}</p>
                                @php
                                    $now = now();
                                    $status = '';
                                    $btnClass = 'btn-secondary';
                                    $btnText = 'Tidak Tersedia';
                                    $hasTaken = \App\Models\Result::where('user_id', auth()->id())->where('exam_id', $exam->id)->exists();

                                    if ($hasTaken) {
                                        $status = 'Sudah Dikerjakan';
                                        $btnClass = 'btn-info';
                                        $btnText = 'Sudah Dikerjakan';
                                    } elseif ($now < $exam->start_time) {
                                        $status = 'Akan dimulai';
                                        $btnClass = 'btn-warning';
                                        $btnText = 'Belum Dimulai';
                                    } elseif ($now >= $exam->start_time && $now <= $exam->end_time) {
                                        $status = 'Aktif';
                                        $btnClass = 'btn-success';
                                        $btnText = 'Mulai Ujian';
                                    } else {
                                        $status = 'Berakhir';
                                        $btnClass = 'btn-danger';
                                        $btnText = 'Sudah Berakhir';
                                    }
                                @endphp
                                <p class="card-text"><strong>Status: {{ $status }}</strong></p>
                                @if($status == 'Aktif')
                                    <a href="/siswa/ujian/{{ $exam->id }}" class="btn {{ $btnClass }}">{{ $btnText }}</a>
                                @else
                                    <button class="btn {{ $btnClass }}" disabled>{{ $btnText }}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/logout" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</body>
</html>
