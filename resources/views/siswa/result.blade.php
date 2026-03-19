<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian - CBT SMKS AL-FALAH NAGREG</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #eef2f7, #f8fafc);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .container {
            max-width: 900px;
        }

        .topbar {
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.6);
            border-radius: 16px;
            padding: 20px;
            margin-top: 30px;
            margin-bottom: 25px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.05);
            text-align: center;
        }

        .topbar h2 {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .card-custom {
            border: none;
            border-radius: 18px;
            padding: 25px;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
            transition: 0.25s;
        }

        .card-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgba(0,0,0,0.08);
        }

        .status-box {
            background: linear-gradient(135deg, #d1e7dd, #e9f7ef);
            border-radius: 12px;
            padding: 15px;
            font-weight: 500;
            color: #0f5132;
            text-align: center;
            margin: 15px 0;
        }

        .btn {
            border-radius: 10px;
            padding: 10px 18px;
            font-size: 0.95rem;
        }

        .action-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .footer-actions {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        @media (max-width: 576px) {
            .footer-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- TOPBAR -->
    <div class="topbar">
        <h2>Hasil Ujian</h2>
        <small class="text-muted">Informasi hasil pengerjaan ujian</small>
    </div>

    <!-- RESULT CARD -->
    <div class="card card-custom">

        <h5 class="fw-semibold mb-2">{{ $result->exam->title }}</h5>

        <div class="status-box">
            Jawaban telah berhasil disubmit
        </div>

        <div class="text-muted" style="font-size: 0.95rem;">
            <div><strong>Tanggal Submit:</strong> {{ $result->created_at->format('d-m-Y H:i') }}</div>
        </div>

    </div>

    <!-- ACTION BUTTON -->
    <div class="footer-actions">

        <a href="/siswa" class="btn btn-primary">
            Kembali ke Dashboard
        </a>

        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="btn btn-danger">
                Logout
            </button>
        </form>

    </div>

</div>

</body>
</html>