@extends('layouts.master')

@section('page_title', 'Class Attendance')

@section('content')

<div class="row justify-content-center">
    <div class="col-10">
        @include('partials.alerts')
        @foreach ($subjectsWithStudents as $subjectId => $data)
            <h3>{{ $data['subject']->name }}</h3>
            <h3>{{ $data['subject']->schedule }}</h3>
            <h3 class="text-center d-flex justify-content-center">
                <form action="{{ route('rfid-reader') }}" method="post" class="mr-2">
                    @csrf
                    <input type="hidden" value="{{ $subjectId }}" name="subject_id">
                    <button type="submit" class="btn btn-primary mx-4">Check Attendance {{ $subjectId }}</button>
                </form>
                <button type="button" class="btn btn-secondary mx-4" data-bs-toggle="modal" data-bs-target="#createStudentModal{{ $subjectId }}">
                    Add Student
                </button>
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

            <!-- Add Student Modal -->
            <div class="modal fade" id="createStudentModal{{ $subjectId }}" tabindex="-1" aria-labelledby="createStudentLabel{{ $subjectId }}" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createStudentLabel{{ $subjectId }}">Add New Student</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="border border-dark rounded p-2 mt-4">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <label for="search_student" class="form-label">Filter Students</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" name="search_student" id="search_student" class="form-control" placeholder="Enter Either Name, Grade, or Section">
                                    </div>
                                    <div class="col">
                                        <button id="search_button" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
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
                            <form action="{{ route('add-student') }}" method="post">
                                @csrf
                                <input type="hidden" name="student_id" id="student_id" >
                                <input type="hidden" name="subject_id" id="subject_id" value="{{ $subjectId }}">
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
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Student</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    let searchButton = document.getElementById('search_button');

    if(searchButton){
        searchButton.addEventListener('click',()=>{

            let searchInput = document.getElementById('search_student').value;


            fetch("{{ route('search') }}", {

                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({search: searchInput})


            })
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                if(data.success){
                    let studentData = '';
                    Object.values(data.results).forEach((values, key) => {
                        studentData += `
                                        <tr data-key="${key}" class="student">
                                        <td>${values.first_name}</td>
                                        <td>${values.last_name}</td>
                                        <td>${values.middle_name}</td>
                                        <td>${values.grade}</td>
                                        <td>${values.section}</td>
                                        </tr>`;
                    });
                    
                    document.getElementById('students_searched').innerHTML = studentData;
                    clickStudent(data.results);
                }

            });
            function clickStudent(students){

                document.querySelectorAll('.student').forEach((values) => {
                    values.addEventListener('click', function() {

                        console.log('clcik');
                        let student = students[parseInt(this.getAttribute('data-key'),10)];


                        document.getElementById('student_id').value = student.id;
                        document.getElementById('first_name').value = student.first_name;
                        document.getElementById('last_name').value = student.last_name;
                        document.getElementById('middle_name').value = student.middle_name;
                        document.getElementById('section').value = student.section;
                        document.getElementById('grade').value = student.grade;

                    });

                });


            }

        });
    }

});


</script>

@endsection
