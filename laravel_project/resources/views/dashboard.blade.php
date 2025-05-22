<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - My Application</title>
    {{-- User will add their CSS links here --}}
    <style>
        body { font-family: sans-serif; }
        .container { width: 80%; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .error-message { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Dashboard</h1>
            <a href="{{ route('logout') }}">Logout</a>
        </div>

        {{-- Display general errors if any --}}
         @if($errors->has('auth'))
            <p class="error-message">{{ $errors->first('auth') }}</p>
        @endif

        <p>Welcome to your dashboard!</p>
        
        {{-- Example of displaying session data --}}
        @if(Session::has('user_email'))
            <p>Your email: {{ Session::get('user_email') }}</p>
        @endif
        @if(Session::has('user_id'))
            <p>Your ID: {{ Session::get('user_id') }}</p>
        @endif
        
        <p><small>User will migrate their full dashboard HTML content and logic here, replacing this basic structure.</small></p>
        {{-- User will add their dynamic content here, potentially passed from DashboardController --}}

    </div>
</body>
</html>
