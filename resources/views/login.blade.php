<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CBT SPMB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            margin: 0;
            display: flex;
        }
        .left-side {
            background-image: url('/img/SMK.png');
            background-size: contain;
            background-position: 70% center;
            background-repeat: no-repeat;
            height: 100vh;
        }
        .right-side {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        .login-card {
            width: 100%;
            max-width: 500px;
        }
        .card-title {
            font-size: 2rem;
            margin-bottom: 2rem;
        }
        .form-label {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .form-control {
            font-size: 1.1rem;
            padding: 0.75rem;
            height: auto;
        }
        .btn {
            font-size: 1.2rem;
            padding: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-md-6 left-side d-none d-md-block"></div>
            <div class="col-md-6 right-side">
                <div class="login-card">
                    <div class="card shadow">
                        <div class="card-body p-5">
                            <h3 class="card-title text-center">CBT SPMB <br>SMK AL-FALAH NAGREG</h3>
                            <form method="POST" action="/login">
                                @csrf
                                <div class="mb-4">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" id="username" placeholder="Username" required>
                                </div>
                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
