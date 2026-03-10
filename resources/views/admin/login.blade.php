<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.login.title') }} - NovaRadio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: hsl(0, 0%, 99%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            -webkit-font-smoothing: antialiased;
        }
        .login-card {
            background: white;
            padding: 48px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid hsl(220, 13%, 91%);
            width: 100%;
            max-width: 400px;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-logo i {
            font-size: 48px;
            color: hsl(14, 100%, 50%);
            margin-bottom: 16px;
        }
        .login-logo h1 {
            font-size: 24px;
            font-weight: 600;
            font-family: 'Roboto Mono', monospace;
            color: hsl(220, 20%, 14%);
        }
        .login-logo h1 .highlight {
            color: hsl(14, 100%, 50%);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 500;
            font-family: 'Roboto Mono', monospace;
            color: hsl(220, 20%, 14%);
        }
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid hsl(220, 13%, 91%);
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: hsl(14, 100%, 50%);
            box-shadow: 0 0 0 3px hsla(14, 100%, 50%, 0.1);
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: hsl(14, 100%, 50%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            font-family: 'Roboto Mono', monospace;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-login:hover {
            background: hsl(14, 100%, 45%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px hsla(14, 100%, 50%, 0.3);
        }
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 3px solid;
        }
        .alert-error {
            background: hsl(0, 86%, 97%);
            color: hsl(0, 72%, 38%);
            border-left-color: #ef4444;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <i class="fas fa-broadcast-tower"></i>
            <h1>NovaRadio<span class="highlight">CMS</span></h1>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="form-group">
                <label>{{ __('auth.login.email') }}</label>
                <input type="email" name="email" required autofocus>
            </div>
            <div class="form-group">
                <label>{{ __('auth.login.password') }}</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> {{ __('auth.login.sign_in') }}
            </button>
        </form>
    </div>
</body>
</html>
