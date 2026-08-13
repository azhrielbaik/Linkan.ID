<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('shortlink.password_protected') }} - Linkan.id</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f6fa;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .auth-card {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .auth-icon {
            font-size: 40px;
            color: #FF9040;
            margin-bottom: 20px;
        }
        h2 { margin: 0 0 10px 0; font-weight: 800; color: #181818; }
        p { color: #666; font-size: 14px; margin-bottom: 30px; line-height: 1.6; }
        
        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #181818;
            margin-bottom: 8px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 15px;
            font-family: inherit;
            box-sizing: border-box;
            transition: all 0.2s;
        }
        .form-group input:focus {
            border-color: #FF9040;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 144, 64, 0.1);
        }
        .btn-submit {
            width: 100%;
            background: #FF9040;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 16px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-submit:hover {
            background: #e07b33;
        }
        .error-message {
            color: #dc2626;
            background: #fee2e2;
            padding: 10px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .footer-logo {
            margin-top: 30px;
            font-size: 13px;
            font-weight: 700;
            color: #aaa;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="auth-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h2>{{ __('shortlink.locked_link') }}</h2>
        <p>{!! __('shortlink.locked_desc', ['url' => '<strong>Linkan.id/' . $shortlink->slug . '</strong>']) !!}</p>

        @if($errors->has('password'))
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first('password') }}
            </div>
        @endif

        <form action="{{ route('shortlink.password.verify', $shortlink->slug) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="password">{{ __('shortlink.password') }}</label>
                <input type="password" name="password" id="password" required placeholder="{{ __('shortlink.enter_password') }}">
            </div>
            <button type="submit" class="btn-submit">{{ __('shortlink.open_link') }} <i class="fas fa-arrow-right" style="margin-left: 6px;"></i></button>
        </form>

        <div class="footer-logo">Linkan.id</div>
    </div>

</body>
</html>
