<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #eef2f7, #f8fafc);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* SIDEBAR (SAMA DASHBOARD) */
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

        /* MAIN */
        .main {
            margin-left: 280px;
            padding: 30px;
        }

        /* TOPBAR */
        .topbar {
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(12px);
            padding: 15px 20px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        /* CARD */
        .card {
            border: none;
            border-radius: 18px;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        /* FORM */
        .form-control, .form-select, .btn {
            border-radius: 10px;
        }

        h2 {
            font-weight: 600;
            color: #343a40;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-title">CBT Admin</div>

    <a href="/admin"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="/admin/soal"><i class="fas fa-file-alt"></i> Kelola Soal</a>
    <a href="/admin/exam"><i class="fas fa-clipboard-list"></i> Kelola Ujian</a>
    <a href="/admin/users" class="active"><i class="fas fa-users"></i> Kelola User</a>
    <a href="/admin/schedule"><i class="fas fa-calendar"></i> Jadwal Ujian</a>
    <a href="/admin/results"><i class="fas fa-chart-bar"></i> Monitoring Hasil</a>

    <form method="POST" action="/logout" class="logout">
        @csrf
        <button class="btn btn-danger w-100">Logout</button>
    </form>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit User</h5>
    </div>

    <h2 class="mb-3">Edit User</h2>

    <!-- FORM -->
    <div class="card p-4">
        <form method="POST" action="/admin/users/{{ $user->id }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" required>
                    <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Kelas</label>
                <select name="kelas" class="form-select">
                    <option value="">Pilih Kelas</option>
                    <option value="X DKV" {{ $user->kelas == 'X DKV' ? 'selected' : '' }}>X DKV</option>
                    <option value="XI DKV" {{ $user->kelas == 'XI DKV' ? 'selected' : '' }}>XI DKV</option>
                    <option value="XII DKV" {{ $user->kelas == 'XII DKV' ? 'selected' : '' }}>XII DKV</option>
                    <option value="X PPLG" {{ $user->kelas == 'X PPLG' ? 'selected' : '' }}>X PPLG</option>
                    <option value="XI PPLG" {{ $user->kelas == 'XI PPLG' ? 'selected' : '' }}>XI PPLG</option>
                    <option value="XII RPL" {{ $user->kelas == 'XII RPL' ? 'selected' : '' }}>XII RPL</option>
                </select>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-primary">Update User</button>
                <a href="/admin/users" class="btn btn-secondary">Batal</a>
            </div>

        </form>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>