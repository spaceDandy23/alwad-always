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
            <form action="{{route('register-tag')}}" method="post" id="stop_auto_submit">
                @csrf
                <label for="RFID_tag" class="form-label">RFID Tag</label>
                <input type="number" name="rfid_tag" id="rfid_tag" class="form-control">
                <label for="student_id" class="form-label">Student ID</label>
                <select name="student_id" id="student_id" class="form-select">
                    <option value="">Select Student ID</option>
                    @foreach($students as $student)
                        <option value="{{$student->id}}" name="student_id">{{$student->id}}</option>
                    @endforeach
                </select>
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control" readonly>
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control" readonly>
                <label for="grade" class="form-label">Grade</label>
                <input type="text" name="grade" id="grade" class="form-control mb-2" readonly>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>



<script>


document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('stop_auto_submit');
        form.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });


        var students = @json($students);

        var studentSelect = document.getElementById('student_id');
        studentSelect.addEventListener('change', function() {
            var selectedId = studentSelect.value;
            if (selectedId) {
                var selectedStudent = students.find((student) => {
                    return student.id.toString() === selectedId;
                });
                if (selectedStudent) {
                    document.getElementById('first_name').value = selectedStudent.first_name
                    document.getElementById('last_name').value = selectedStudent.last_name;
                    document.getElementById('grade').value = selectedStudent.grade;
                }
            } else {
                document.getElementById('first_name').value = '';
                document.getElementById('last_name').value = '';
                document.getElementById('grade').value = '';
            }
        });
    });
</script>

@endsection