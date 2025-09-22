<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Your App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Logo (Optional) -->
                <div class="text-center mb-4">
                    <img src="{{ asset('assets/images/getwell.png') }}" alt="Logo" style="max-width: 150px;">
                </div>

                <div class="card">
                    <div class="text-center mt-4">Login</div>

                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group">
                                <label for="email">Email or Phone</label>
                                <input id="email" type="text" class="form-control" name="email"
                                    value="{{ old('email') }}" required autofocus
                                    placeholder="Enter email or phone number">
                            </div>


                            <div class="form-group mt-3">
                                <label for="password">Password</label>
                                <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                            </div>

                            <div class="form-group mt-4 d-flex justify-content-between align-items-center">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm text-primary">Forgot your
                                        password?</a>
                                @endif
                                <button type="submit" class="btn btn-primary px-4">Log in</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
