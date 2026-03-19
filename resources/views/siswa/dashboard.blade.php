<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - CBT SMKS AL-FALAH NAGREG</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            position: relative;
            font-size: 1.05em;
            min-height: 100vh;
            background: linear-gradient(135deg, #eef2f7, #f8fafc);
            font-family: 'Inter', sans-serif;
        }

        body::before {
            content: '';
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            height: 90%;
            background-image: url('/img/bgweb.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.2;
            z-index: -1;
        }

        .wrapper {
            padding: 40px 20px;
        }

        .card {
            border: none;
            border-radius: 18px;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
            transition: 0.3s;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        .card-title {
            font-size: 1.2em;
            font-weight: 600;
        }

        .card-text {
            font-size: 0.95em;
            margin-bottom: 6px;
        }

        .btn {
            border-radius: 10px;
        }

        .header {
            margin-bottom: 30px;
        }

        .logout {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container wrapper">

        <div class="header text-center">
            <h2>Dashboard Siswa</h2>
        </div>

        @if($exams->isEmpty())
            <div class="alert alert-info text-center">
                Tidak ada ujian yang tersedia.
            </div>
        @else
            <div class="row g-4">
                @foreach($exams as $exam)
                    <div class="col-md-6 col-lg-4">
                        <div class="card p-4">
                            <h5 class="card-title">{{ $exam->title }}</h5>

                            <p class="card-text">Durasi: {{ $exam->duration }} menit</p>
                            <p class="card-text">Waktu: {{ $exam->start_time }} s/d {{ $exam->end_time }}</p>

                            @php
                                $now = now();
                                $status = '';
                                $btnClass = 'btn-secondary';
                                $btnText = 'Tidak Tersedia';

                                $hasTaken = \App\Models\Result::where('user_id', auth()->id())
                                    ->where('exam_id', $exam->id)->exists();

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

                            <p class="card-text mt-2">
                                <strong>Status: {{ $status }}</strong>
                            </p>

                            <div class="mt-3">
                                @if($status == 'Aktif')
                                    <a href="/siswa/ujian/{{ $exam->id }}" class="btn {{ $btnClass }} w-100">
                                        {{ $btnText }}
                                    </a>
                                @else
                                    <button class="btn {{ $btnClass }} w-100" disabled>
                                        {{ $btnText }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/logout" class="text-center logout">
            @csrf
            <button type="submit" class="btn btn-danger px-4">Logout</button>
        </form>

    </div>
</body>
</html>