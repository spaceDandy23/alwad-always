<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="/">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                @if (Auth::check())
                    <!-- Links for authenticated users -->
                    @if (Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('students.index') }}">Students</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('subjects.index') }}">Subjects</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}">Teachers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register-tag') }}">Register Student</a>
                        </li>
                    @endif
                    @if (Auth::user()->isTeacher())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('class-index') }}">Class</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('attendance') }}">Attendance</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('students-strikes') }}">Strikes</a>
                        </li>
                        @if(Session::has('subject'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('rfid-reader') }}">Check Attendance</a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('create-class') }}">Create Class</a>
                        </li>
                    @endif
                @endif
            </ul>
            <ul class="navbar-nav ms-auto">
                @if (Auth::check())
                    <li class="nav-item">
                        <a class="nav-link">Hello {{Auth::user()->name}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to log out?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Log out</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container mt-3 mb-4">
    @yield('content')
    <!-- @include('partials.alerts') -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</div>
</body>
</html>
