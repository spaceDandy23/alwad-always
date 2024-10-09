@extends('layouts.master')

@section('page_title', 'Register')

@section('content')

<div class="row justify-content-center"> 
    <div class="card col-6 px-0">
        <div class="card-header text-center">
            <h3>Register</h3>
        </div>
        <div class="card-body">
            @include('partials.alerts')

            <div class="border border-dark rounded p-2 mt-4">
                <div class="row align-items-center">
                    <div class="col">
                        <label for="search_student" class="form-label">Search Student</label>
                    </div>
                    <div class="col-8">
                        <input type="text" name="search_student" id="search_student" class="form-control" placeholder="Enter Student">
                    </div>
                    <div class="col">
                        <button id="search_button" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </div>
            <form action="{{route('register-tag')}}" id="form_register" method="POST">
                @csrf
                <input type="hidden" name="student_id" id="student_id" >
                <label for="RFID_tag" class="form-label">RFID Tag</label>
                <input type="number" name="rfid_tag" id="rfid_tag" class="form-control">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control" readonly>
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control" readonly>
                <label for="middle_name" class="form-label">Middle Name</label>
                <input type="text" name="middle_name" id="middle_name" class="form-control" readonly>
                <label for="grade" class="form-label">Grade</label>
                <input type="text" name="grade" id="grade" class="form-control" readonly>
                <label for="section" class="form-label">Section</label>
                <input type="text" name="section" id="section" class="form-control mb-2" readonly>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>


            <table class="table table-striped">
                <thead>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Middle Name</th>
                    <th scope="col">Grade</th>
                    <th scope="col">Section</th>
                </thead>
                <tbody id="students_searched">



                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const routes = {
        search: "{{ route('search') }}",
        csrfToken: "{{ csrf_token() }}",

    };
</script>


<script type="module" src="{{ asset('js/registerStudent.js') }}">



</script>

@endsection