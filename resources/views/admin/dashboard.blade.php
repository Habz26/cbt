<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - CBT SMKS AL-FALAH NAGREG</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #eef2f7, #f8fafc);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 240px;
            height: calc(100% - 40px);
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(14px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.07);
            padding: 20px;
            z-index: 1000;
        }

        .sidebar-title {
            font-weight: 600;
            margin-bottom: 25px;
            color: #343a40;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            margin-bottom: 8px;
            border-radius: 10px;
            color: #495057;
            text-decoration: none;
            transition: 0.25s;
        }

        .sidebar a:hover {
            background: rgba(13,110,253,0.1);
            color: #0d6efd;
        }

        .sidebar a.active {
            background: #0d6efd;
            color: #fff;
        }

        .logout {
            position: absolute;
            bottom: 20px;
            width: calc(100% - 40px);
        }

        .main {
            margin-left: 280px;
            padding: 30px;
        }

        .topbar {
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(12px);
            padding: 15px 20px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .card {
            border: none;
            border-radius: 18px;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        .card-title {
            font-weight: 600;
            color: #495057;
        }

        .display-4 {
            font-weight: 700;
            color: #212529;
        }

        .btn, .form-select {
            border-radius: 10px;
        }

        .table thead {
            background: #f1f3f5;
        }

        canvas {
            padding-top: 10px;
        }

        h2 {
            font-weight: 600;
            color: #343a40;
        }
    </style>
</head>

<body>

<div class="sidebar">
    <div class="sidebar-title">CBT Admin</div>

    <a href="/admin" class="active"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="/admin/exam"><i class="fas fa-clipboard-list"></i> Kelola Ujian</a>
    <a href="/admin/soal"><i class="fas fa-file-alt"></i> Kelola Soal</a>
    <a href="/admin/users"><i class="fas fa-users"></i> Kelola User</a>
    <a href="/admin/schedule"><i class="fas fa-calendar"></i> Jadwal Ujian</a>
    <a href="/admin/results"><i class="fas fa-chart-bar"></i> Monitoring Hasil</a>

    <form method="POST" action="/logout" class="logout">
        @csrf
        <button class="btn btn-danger w-100">Logout</button>
    </form>
</div>

<div class="main">

    <div class="topbar d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Dashboard Admin</h5>

        <form method="GET" action="/admin" class="d-flex gap-2">
            <select name="kelas" class="form-select">
                <option value="">Semua Kelas</option>
                <option value="X DKV" {{ $kelas == 'X DKV' ? 'selected' : '' }}>X DKV</option>
                <option value="XI DKV" {{ $kelas == 'XI DKV' ? 'selected' : '' }}>XI DKV</option>
                <option value="XII DKV" {{ $kelas == 'XII DKV' ? 'selected' : '' }}>XII DKV</option>
                <option value="X PPLG" {{ $kelas == 'X PPLG' ? 'selected' : '' }}>X PPLG</option>
                <option value="XI PPLG" {{ $kelas == 'XI PPLG' ? 'selected' : '' }}>XI PPLG</option>
                <option value="XII PPLG" {{ $kelas == 'XII PPLG' ? 'selected' : '' }}>XII PPLG</option>
                <option value="X RPL" {{ $kelas == 'X RPL' ? 'selected' : '' }}>X RPL</option>
                <option value="XI RPL" {{ $kelas == 'XI RPL' ? 'selected' : '' }}>XI RPL</option>
                <option value="XII RPL" {{ $kelas == 'XII RPL' ? 'selected' : '' }}>XII RPL</option>
            </select>

            <button class="btn btn-primary">Filter</button>

            @if($kelas)
                <a href="/admin" class="btn btn-secondary">Reset</a>
            @endif
        </form>
    </div>

    <h2 class="mb-3">Dashboard Admin</h2>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Total Ujian</h6>
                <h2>{{ $examCount }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Total Soal</h6>
                <h2>{{ $questionCount }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Total User</h6>
                <h2>{{ $userCount }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Total Hasil</h6>
                <h2>{{ $resultCount }}</h2>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12 d-flex flex-column gap-3">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title">Rata-rata Skor Siswa</h5>

                    @if(count($examsForChart) > 0)
                    <select id="examSelector" class="form-select form-select-sm" style="width:auto;">
                        @foreach($examsForChart as $exam)
                            <option value="{{ $loop->index }}">{{ $exam['title'] }}</option>
                        @endforeach
                    </select>
                    @endif
                </div>
                <canvas id="studentScoreChart"></canvas>
            </div>

            <div class="card p-3">
                <h5 class="card-title">Jumlah Soal Benar Siswa</h5>
                <canvas id="studentCorrectChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card mt-4 p-3">
        <h5 class="card-title">Soal per Ujian</h5>
        <canvas id="examChart"></canvas>
    </div>

    <div class="mt-4">
        @if(count($examAnalytics) > 0)
        <div class="card p-3">
            <h5 class="card-title" id="analyticsTableTitle">
                Analytics Siswa - {{ $examAnalytics[0]['exam']->title }}
            </h5>

            <table class="table table-hover" id="analyticsTable">
                <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Email</th>
                    <th>Skor</th>
                    <th>Rata-rata Skor</th>
                    <th>Jumlah Soal Benar</th>
                </tr>
                </thead>
                <tbody id="analyticsTableBody">
                @foreach($examAnalytics[0]['analytics'] as $analytics)
                    <tr>
                        <td>{{ $analytics['user']->name }}</td>
                        <td>{{ $analytics['user']->email }}</td>
                        <td>{{ $analytics['totalScore'] }}</td>
                        <td>{{ $analytics['averageScore'] }}</td>
                        <td>{{ $analytics['totalCorrect'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="card p-3">
            <h5 class="card-title">Analytics Siswa</h5>
            <p class="text-muted">Belum ada data hasil ujian.</p>
        </div>
        @endif
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
const examsForChart = @json($examsForChart);
const examAnalytics = @json($examAnalytics);

const examCtx = document.getElementById('examChart').getContext('2d');

const examChart = new Chart(examCtx, {
    type: 'bar',
    data: {
        labels: @json($examStats->pluck('title')),
        datasets: [{
            label: 'Jumlah Soal',
            data: @json($examStats->pluck('questions_count')),
            borderWidth: 1
        }]
    },
    options: { responsive: true }
});

function updateCharts(examIndex) {
    const examData = examsForChart[examIndex];
    if (!examData) return;

    studentScoreChart.data.labels = examData.studentNames;
    studentScoreChart.data.datasets[0].data = examData.averageScores;
    studentScoreChart.update();

    studentCorrectChart.data.labels = examData.studentNames;
    studentCorrectChart.data.datasets[0].data = examData.totalCorrect;
    studentCorrectChart.update();
}

const studentScoreChart = new Chart(document.getElementById('studentScoreChart'), {
    type: 'bar',
    data: { labels: [], datasets: [{ label: 'Rata-rata Skor', data: [] }] },
    options: { responsive: true }
});

const studentCorrectChart = new Chart(document.getElementById('studentCorrectChart'), {
    type: 'bar',
    data: { labels: [], datasets: [{ label: 'Jumlah Soal Benar', data: [] }] },
    options: { responsive: true }
});

function updateAnalyticsTable(examIndex) {
    const analyticsData = examAnalytics[examIndex];
    if (!analyticsData) return;

    document.getElementById('analyticsTableTitle').textContent =
        'Analytics Siswa - ' + analyticsData.exam.title;

    const tableBody = document.getElementById('analyticsTableBody');
    tableBody.innerHTML = '';

    analyticsData.analytics.forEach(a => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${a.user.name}</td>
            <td>${a.user.email}</td>
            <td>${a.totalScore}</td>
            <td>${a.averageScore}</td>
            <td>${a.totalCorrect}</td>
        `;
        tableBody.appendChild(row);
    });
}

const selector = document.getElementById('examSelector');

if (selector) {
    selector.addEventListener('change', function() {
        updateCharts(this.value);
        updateAnalyticsTable(this.value);
    });

    if (examsForChart.length > 0) {
        updateCharts(0);
        updateAnalyticsTable(0);
    }
}
</script>

</body>
</html>