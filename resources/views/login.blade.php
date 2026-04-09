<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CBT SMKS AL-FALAH NAGREG</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #eef2f7, #f8fafc);
            height: 100vh;
            margin: 0;
            display: flex;
            font-family: 'Inter', sans-serif;
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

        .card {
            border: none;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 2rem;
            margin-bottom: 2rem;
            font-weight: 600;
            color: #343a40;
        }

        .form-label {
            font-size: 1rem;
            font-weight: 600;
            color: #495057;
        }

        .form-control {
            font-size: 1rem;
            padding: 0.75rem;
            border-radius: 10px;
        }

        .btn {
            font-size: 1.1rem;
            padding: 0.75rem;
            border-radius: 10px;
        }

        .btn-primary {
            background: #0d6efd;
            border: none;
        }

        .btn-primary:hover {
            background: #0b5ed7;
        }
    </style>
</head>

<body>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-md-6 left-side d-none d-md-block"></div>

            <div class="col-md-6 right-side">
                <div class="login-card">
                    <div class="card">
                        <div class="card-body p-5">
                            @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            <h3 class="card-title text-center">
                                CBT <br> SMKS AL-FALAH NAGREG
                            </h3>

                            <form method="POST" action="/login">
                                @csrf

                                <div class="mb-4">
                                    <label for="email" class="form-label">E-Mail</label>
                                    <input type="email" name="email" class="form-control" id="email"
                                        placeholder="Masukkan E-Mail anda" required>
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="password"
                                        placeholder="Password" required>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    Login
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>

</html>
