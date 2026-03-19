<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #eef2f7, #f8fafc);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* SIDEBAR (SAMA PERSIS DASHBOARD) */
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

        /* CARD (SAMA DASHBOARD) */
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

        /* FORM */
        .form-control, .form-select, .btn {
            border-radius: 10px;
        }

        /* TABLE */
        .table thead {
            background: #f1f3f5;
        }

        .table tbody tr {
            transition: 0.2s;
        }

        .table tbody tr:hover {
            background: rgba(13,110,253,0.05);
        }

        h5, h6 {
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
    <div class="topbar">
        <h5 class="mb-0">Kelola User</h5>
        <small class="text-muted">Manajemen akun pengguna</small>
    </div>

    <!-- FORM TAMBAH -->
    <div class="card p-4 mb-4">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="/admin/users">
            @csrf
            <div class="row g-3">
                <div class="col-md-2">
                    <input type="text" name="name" placeholder="Nama"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-2">
                    <input type="text" name="username" placeholder="Username"
                        class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username') }}" required>
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-2">
                    <input type="email" name="email" placeholder="Email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-2">
                    <input type="password" name="password" placeholder="Password"
                        class="form-control @error('password') is-invalid @enderror" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-2">
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="student">Student</option>
                        <option value="admin">Admin</option>
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-2">
                    <select name="kelas" class="form-select">
                        <option value="">Kelas</option>
                        <option value="X DKV">X DKV</option>
                        <option value="XI DKV">XI DKV</option>
                        <option value="XII DKV">XII DKV</option>
                        <option value="X PPLG">X PPLG</option>
                        <option value="XI PPLG">XI PPLG</option>
                        <option value="XII RPL">XII RPL</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Tambah</button>
                </div>
            </div>
        </form>
    </div>

    <!-- IMPORT -->
<div class="card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Import Users</h6>
    </div>

    <form method="POST" action="/admin/users/import" enctype="multipart/form-data">
        @csrf
        <div class="row g-3 align-items-center">

<div class="col-md-8">
                <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                <small class="text-muted">Format Excel: name, username, email, password, role (header row required)</small>
            </div>

            <div class="col-md-4">
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-upload me-1"></i> Import Excel
                </button>
            </div>

        </div>
    </form>
</div>

    <!-- TABLE -->
    <div class="card p-4">
        <h6 class="mb-3">Data User</h6>

        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Kelas</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->plain_password ?? 'N/A' }}</td>
                            <td>{{ $user->role }}</td>
                            <td>{{ $user->kelas ?? '-' }}</td>
                            <td class="text-end">
                                <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-sm btn-light">Edit</a>
                                <form method="POST" action="/admin/users/{{ $user->id }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger"
                                        onclick="return confirm('Yakin hapus user ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>