<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .card { margin: 20px; }
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
        }
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }
        .nav-link.active {
            background-color: #007bff;
            border-radius: 5px;
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
                        <a class="nav-link active" href="/admin/analytics">Analytics</a>
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
        <h2 class="mt-4">Analytics</h2>

        <div class="row">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Ujian</h5>
                        <p class="card-text display-4">{{ $examCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Soal</h5>
                        <p class="card-text display-4">{{ $questionCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total User</h5>
                        <p class="card-text display-4">{{ $userCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Hasil</h5>
                        <p class="card-text display-4">{{ $resultCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Soal per Ujian</h5>
                        <canvas id="examChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">User per Role</h5>
                        <canvas id="userChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Analytics Siswa</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Siswa</th>
                                    <th>Email</th>
                                    <th>Total Ujian</th>
                                    <th>Total Skor</th>
                                    <th>Rata-rata Skor</th>
                                    <th>Jumlah Soal Benar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($studentAnalytics as $analytics)
                                    <tr>
                                        <td>{{ $analytics['user']->name }}</td>
                                        <td>{{ $analytics['user']->email }}</td>
                                        <td>{{ $analytics['totalExams'] }}</td>
                                        <td>{{ $analytics['totalScore'] }}</td>
                                        <td>{{ $analytics['averageScore'] }}</td>
                                        <td>{{ $analytics['totalCorrect'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const examCtx = document.getElementById('examChart').getContext('2d');
        const examChart = new Chart(examCtx, {
            type: 'bar',
            data: {
                labels: @json($examStats->pluck('title')),
                datasets: [{
                    label: 'Jumlah Soal',
                    data: @json($examStats->pluck('questions_count')),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const userCtx = document.getElementById('userChart').getContext('2d');
        const userChart = new Chart(userCtx, {
            type: 'pie',
            data: {
                labels: @json($userStats->pluck('role')),
                datasets: [{
                    data: @json($userStats->pluck('count')),
                    backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'],
                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)'],
                    borderWidth: 1
                }]
            }
        });

        const studentScoreCtx = document.getElementById('studentScoreChart').getContext('2d');
        const studentScoreChart = new Chart(studentScoreCtx, {
            type: 'bar',
            data: {
                labels: @json($studentNames),
                datasets: [{
                    label: 'Rata-rata Skor',
                    data: @json($studentAverageScores),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const studentCorrectCtx = document.getElementById('studentCorrectChart').getContext('2d');
        const studentCorrectChart = new Chart(studentCorrectCtx, {
            type: 'bar',
            data: {
                labels: @json($studentNames),
                datasets: [{
                    label: 'Jumlah Soal Benar',
                    data: @json($studentTotalCorrect),
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
