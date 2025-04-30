<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cape Scub</title>
    <link rel="stylesheet" href="{{asset('adminasset/dist/css/adminlte.min.css')}}">

    <link rel="stylesheet" href="{{ asset('adminasset/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminasset/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminasset/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">


    <style>
        #userList {
            top: 100%;
            left: 0;
            width: 100%;
            max-height: 250px;
            overflow-y: auto;
            background: white;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

    </style>
</head>
<body class="">
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header text-white text-center bg-primary fs-4">
                <span id="form-title">Login</span>
            </div>

            <!-- Login Form -->
            <form action="{{ route('loggingin') }}" method="POST" id="login-form">
                @csrf
                <div class="card-body">
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="{{ old('username') }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="show-password" />
                            <label class="form-check-label" for="show-password">Show Password</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>

    <script>
       document.addEventListener("DOMContentLoaded", function() {
            var showPasswordCheckbox = document.getElementById('show-password');
            var passwordField = document.getElementById('password');

            showPasswordCheckbox.addEventListener('change', function() {
                if (showPasswordCheckbox.checked) {
                    passwordField.type = 'text';
                } else {
                    passwordField.type = 'password';
                }
            });
        });

    </script>
</body>
</html>
