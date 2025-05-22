<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - My Application</title>
    {{-- User will add their CSS links here --}}
    <style>
        .error-message { color: red; margin-bottom: 10px; }
        .status-message { color: green; margin-bottom: 10px; }
        body { font-family: sans-serif; }
        .container { width: 300px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="password"], input[type="email"] { width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        {{-- Display general login errors (e.g., 'Invalid credentials') --}}
        @if($errors->has('login'))
            <p class="error-message">{{ $errors->first('login') }}</p>
        @endif
        @if($errors->has('auth'))
            <p class="error-message">{{ $errors->first('auth') }}</p>
        @endif


        {{-- Display status message (e.g., after logout) --}}
        @if(session('status'))
            <p class="status-message">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf {{-- CSRF protection token --}}

            <div>
                <label for="email">Email Address</label> {{-- Assuming email, user can change to username --}}
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
                @if($errors->has('email'))
                    <p class="error-message">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                @if($errors->has('password'))
                    <p class="error-message">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <div>
                <button type="submit">Login</button>
            </div>
        </form>
        <p><small>User will migrate their full HTML form structure here, replacing this basic one.</small></p>
    </div>
</body>
</html>
