<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Laravel Telescope</title>
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #1f2937;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
            color: #111827;
        }
        label {
            display: block;
            margin-top: 1rem;
            font-weight: 500;
        }
        input {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.25rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            background-color: #f9fafb;
        }
        button {
            margin-top: 1.5rem;
            width: 100%;
            padding: 0.75rem;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
        }
        button:hover {
            background-color: #1d4ed8;
        }
        .error {
            color: #dc2626;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Laravel Telescope Login</h2>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('telescope.login.post') }}">
            @csrf
            <label for="login">Login</label>
            <input type="text" name="login" id="login" required autofocus>

            <label for="password">Senha</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
