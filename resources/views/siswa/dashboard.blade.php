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
                                    <button class="btn {{ $btnClass }} w-100" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#confirmExamModal"
                                            data-exam-url="/siswa/ujian/{{ $exam->id }}"
                                            data-exam-title="{{ $exam->title }}">
                                        {{ $btnText }}
                                    </button>
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

            <!-- Single Confirmation Modal - Centered Full Overlay -->
            <div class="modal fade" id="confirmExamModal" tabindex="-1" aria-labelledby="confirmExamModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="confirmExamModalLabel">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <span id="modalExamTitle">PETUNJUK PENGERJAAN UJIAN SEKOLAH (CBT)</span>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="modalExamRules" style="font-size: 0.95em; line-height: 1.6; white-space: pre-line;">
                            <!-- Rules content populated by JS -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </button>
                            <button type="button" class="btn btn-success" id="startExamBtn">
                                <i class="bi bi-play-circle me-1"></i>Mulai Ujian
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="/logout" class="text-center logout">
            @csrf
            <button type="submit" class="btn btn-danger px-4">Logout</button>
        </form>

    </div>

    <!-- Bootstrap JS for modals -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Dynamic modal population for exam confirmation
        var confirmModal = document.getElementById('confirmExamModal');
        if (confirmModal) {
            confirmModal.addEventListener('show.bs.modal', function (event) {
                var triggerButton = event.relatedTarget;
                var examTitle = triggerButton.getAttribute('data-exam-title') || 'Ujian';
                var examUrl = triggerButton.getAttribute('data-exam-url');
                var startBtn = document.getElementById('startExamBtn');
                
                // Set title
                document.getElementById('modalExamTitle').textContent = examTitle;
                
                // Rules text (fixed)
                document.getElementById('modalExamRules').textContent = `PETUNJUK PENGERJAAN UJIAN SEKOLAH (CBT)

Harap dibaca dengan seksama sebelum memulai ujian:

-Ujian ini berbasis komputer dan dikerjakan secara mandiri.
-Waktu ujian terbatas, perhatikan timer yang tersedia di layar.
-Setiap soal hanya memiliki satu jawaban yang benar.
-Pilih jawaban dengan mengklik opsi yang tersedia.
-Anda dapat berpindah soal menggunakan tombol navigasi (Next/Previous atau nomor soal).
-Jawaban akan tersimpan otomatis, namun pastikan semua soal telah dijawab sebelum mengakhiri ujian.
-Jangan menutup browser, refresh halaman, atau keluar dari sistem selama ujian berlangsung.
-Jika terjadi kendala teknis, segera hubungi pengawas.
-Dilarang bekerja sama atau menggunakan bantuan dalam bentuk apa pun.
-Klik tombol "Selesai" jika Anda telah menyelesaikan seluruh soal.

PERHATIAN:

Setelah menekan tombol "Selesai", Anda tidak dapat kembali mengerjakan soal.

Silakan klik "Mulai" untuk memulai ujian.`;
                
                // Set redirect URL
                startBtn.onclick = function() {
                    window.location.href = examUrl;
                };
            });
        }
    </script>
</body>
</html>
