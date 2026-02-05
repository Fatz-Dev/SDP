<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SIDAPEG</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink-light: #e8f1fb;
            --pink-medium: #90caf9;
            --pink-dark: #1e88e5;
            --pink-darker: #0d47a1;
            --white: #ffffff;
            --gray-light: #f5f7fa;
            --shadow: rgba(30, 136, 229, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--pink-medium), var(--pink-light));
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 15px 35px var(--shadow);
            width: 100%;
            max-width: 400px;
            padding: 40px;
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo i {
            font-size: 50px;
            color: var(--pink-dark);
            margin-bottom: 10px;
        }

        .logo h1 {
            color: var(--pink-darker);
            font-size: 24px;
            font-weight: 600;
        }

        .login-form h2 {
            color: #333;
            text-align: center;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #666;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-group label i {
            color: var(--pink-dark);
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--pink-light);
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
            outline: none;
        }

        .form-group input:focus {
            border-color: var(--pink-dark);
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
        }

        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
            border-left: 4px solid #c62828;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--pink-dark), var(--pink-darker));
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(30, 136, 229, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            color: #888;
            font-size: 14px;
        }

        .footer strong {
            color: var(--pink-darker);
        }

        .heart {
            color: var(--pink-dark);
            animation: heartbeat 1.5s infinite;
        }

        @keyframes heartbeat {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="login-container">
        <div class="logo">
            <i class="fas fa-user-circle"></i>
            <h1>SIDAPEG</h1>
        </div>

        <div class="login-form">
            <h2>Masuk ke Dashboard</h2>

            @if ($errors->any())
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="Masukkan email" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <div class="footer">
                <p>Test Credentials:</p>
                <p>Email (Admin): <strong>admin@sidapeg.com</strong></p>
                <p>Email (Pegawai): <strong>budi@sidapeg.com</strong></p>
                <p>Password: <strong>password</strong></p>
                <p style="margin-top: 10px;">Made with <span class="heart">💙</span></p>
            </div>
        </div>
    </div>
</body>

</html>
