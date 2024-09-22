@extends('layouts.master')

@section('page_title', 'Class Attendance')

@section('content')

<div class="row justify-content-center">
    <div class="col-10">
        @include('partials.alerts')
        @foreach ($subjectsWithStudents as $subjectId => $data)
            <h3>{{ $data['subject']->name }}</h3>
            <h3>{{ $data['subject']->schedule }}</h3>
            <h3 class="text-center">
                <form action="{{ route('rfid-reader') }}" method="post">
                    @csrf
                    <input type="hidden" value = {{ $subjectId }} name = "subject_id">
                    <button type ="submit" class="btn btn-primary">Check Attendance {{ $subjectId }}</button>
                </form>
            </h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Grade</th>
                        <th scope="col">Section</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['students'] as $student)
                        <tr>
                            <td>{{ $student->first_name }}</td>
                            <td>{{ $student->last_name }}</td>
                            <td>{{ $student->grade }}</td>
                            <td>{{ $student->section }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
</div>
<script>

</script>

@endsection
