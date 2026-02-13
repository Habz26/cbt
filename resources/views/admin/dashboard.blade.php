<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - CBT SPMB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .card { margin: 20px 0; }
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
        .navbar-nav .nav-item {
            margin: 0 5px;
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
        <h2 class="mt-4">Dashboard Admin</h2>

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
            <div class="col-md-6 d-flex flex-column gap-3">
                <div class="card flex-fill" style="margin: 0;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Rata-rata Skor Siswa</h5>
                            @if(count($examsForChart) > 0)
                            <select id="examSelector" class="form-select form-select-sm" style="width: auto;">
                                @foreach($examsForChart as $exam)
                                    <option value="{{ $loop->index }}">{{ $exam['title'] }}</option>
                                @endforeach
                            </select>
                            @endif
                        </div>
                        <canvas id="studentScoreChart"></canvas>
                    </div>
                </div>
                <div class="card flex-fill" style="margin: 0;">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Soal Benar Siswa</h5>
                        <canvas id="studentCorrectChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100" style="margin: 0;">
                    <div class="card-body">
                        <h5 class="card-title">User per Role</h5>
                        <canvas id="userChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Soal per Ujian</h5>
                        <canvas id="examChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @if(count($examAnalytics) > 0)
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title" id="analyticsTableTitle">Analytics Siswa - {{ $examAnalytics[0]['exam']->title }}</h5>
                            <table class="table table-striped" id="analyticsTable">
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
                    </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Analytics Siswa</h5>
                            <p class="text-muted">Belum ada data hasil ujian.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Exam data for charts
        const examsForChart = @json($examsForChart);
        
        // Exam analytics data for table
        const examAnalytics = @json($examAnalytics);
        
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
                layout: {
                    padding: {
                        left: 10,
                        right: 10,
                        top: 10,
                        bottom: 10
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            padding: 10
                        }
                    },
                    x: {
                        ticks: {
                            padding: 10
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            padding: 20
                        }
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

        // Function to update charts based on selected exam
        function updateCharts(examIndex) {
            const examData = examsForChart[examIndex];
            if (!examData) return;
            
            // Update student score chart
            studentScoreChart.data.labels = examData.studentNames;
            studentScoreChart.data.datasets[0].data = examData.averageScores;
            studentScoreChart.update();
            
            // Update student correct chart
            studentCorrectChart.data.labels = examData.studentNames;
            studentCorrectChart.data.datasets[0].data = examData.totalCorrect;
            studentCorrectChart.update();
        }

        // Initialize student score chart
        const studentScoreCtx = document.getElementById('studentScoreChart').getContext('2d');
        const studentScoreChart = new Chart(studentScoreCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Rata-rata Skor',
                    data: [],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                layout: {
                    padding: {
                        left: 10,
                        right: 10,
                        top: 10,
                        bottom: 10
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            padding: 10
                        }
                    },
                    x: {
                        ticks: {
                            padding: 10
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            padding: 20
                        }
                    }
                }
            }
        });

        // Initialize student correct chart
        const studentCorrectCtx = document.getElementById('studentCorrectChart').getContext('2d');
        const studentCorrectChart = new Chart(studentCorrectCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Jumlah Soal Benar',
                    data: [],
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                layout: {
                    padding: {
                        left: 10,
                        right: 10,
                        top: 10,
                        bottom: 10
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            padding: 10
                        }
                    },
                    x: {
                        ticks: {
                            padding: 10
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            padding: 20
                        }
                    }
                }
            }
        });

        // Function to update analytics table based on selected exam
        function updateAnalyticsTable(examIndex) {
            const analyticsData = examAnalytics[examIndex];
            if (!analyticsData) return;
            
            // Update table title
            const tableTitle = document.getElementById('analyticsTableTitle');
            if (tableTitle) {
                tableTitle.textContent = 'Analytics Siswa - ' + analyticsData.exam.title;
            }
            
            // Update table body
            const tableBody = document.getElementById('analyticsTableBody');
            if (tableBody) {
                tableBody.innerHTML = '';
                
                analyticsData.analytics.forEach(function(analytics) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${analytics.user.name}</td>
                        <td>${analytics.user.email}</td>
                        <td>${analytics.totalScore}</td>
                        <td>${analytics.averageScore}</td>
                        <td>${analytics.totalCorrect}</td>
                    `;
                    tableBody.appendChild(row);
                });
            }
        }

        // Add event listener for exam selector
        const examSelector = document.getElementById('examSelector');
        if (examSelector) {
            examSelector.addEventListener('change', function() {
                const examIndex = this.value;
                updateCharts(examIndex);
                updateAnalyticsTable(examIndex);
            });
            // Initialize charts and table with first exam data
            if (examsForChart.length > 0) {
                updateCharts(0);
                updateAnalyticsTable(0);
            }
        }
    </script>
</body>
</html>
