@extends('layouts.master')

@section('page_title', 'Verify')

@section('content')
<div class='card'>
    <div class='card-body' id="card_body">
        <div id="alert">
        </div>
        <h1 id="subject_name">{{ $subject->name ?? '' }}</h1>

        <h5 class='card-title'>Student Details</h5>
        <p class='card-text'>RFID Tag: <span id="rfid_tag"></span></p>
        <p class='card-text'>First Name: <span id="first_name"></span></p>
        <p class='card-text'>Last Name: <span id="last_name"></span></p>
        <p class='card-text'>Grade: <span id="grade"></span></p>
        <p class='card-text'>Section: <span id="section"></span></p>

        <form id='tag_form'>
            @csrf
            <label for='rfid_tag' class='form-label'>RFID Tag</label>
            <input type='text' name='rfid_tag' class='form-control mb-2' id='rfid_field'>
            <input type="hidden" value="{{ $subject->id }}" id="subject_id">
            <button id='submit-btn' class='btn btn-primary' type='submit'>Verify</button>
        </form>
        <table class="table table-striped">
            <thead>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Grade</th>
                <th scope="col">Section</th>
            </thead>
            <tbody id="students_list">


            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('rfid_field').focus();
        
        let div = document.getElementById('alert');
        let students = [];

        document.getElementById('tag_form').addEventListener('submit', (event) => {
            event.preventDefault();
            const formData = new FormData(document.getElementById('tag_form'));
            const url = "{{ route('rfid-reader.verify', $subject->id) }}";

            fetch(url, {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data);
                    updateTextContent(data);
                    generateStudents(data);
                    
                } else{
                    alert(data);
                    clearTextContent();
                    console.error('Student not found or other error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
        function alert(data){
            if(data.success){
                div.className = 'alert alert-success';;
                div.innerHTML = `<li>${data.message}</li>`;
            }
            else{
                div.className = 'alert alert-danger';
                div.innerHTML = `<li>${data.message}</li>`;
            }
        }
        function updateTextContent(data) {
            document.getElementById('rfid_tag').innerText = data.student.rfid_tag;
            document.getElementById('first_name').innerText = data.student.first_name;
            document.getElementById('last_name').innerText = data.student.last_name;
            document.getElementById('grade').innerText = data.student.grade;
            document.getElementById('section').innerText = data.student.section;
        }

        function clearTextContent() {
            document.getElementById('rfid_tag').innerText = '';
            document.getElementById('first_name').innerText = '';
            document.getElementById('last_name').innerText = '';
            document.getElementById('grade').innerText = '';
        }
        function generateStudents(data){
            students.push(data.student);
            console.log(students);
            let htmlStudents = ``;
            for(let student of students){
                htmlStudents += `<tr>
                                    <td>${student.first_name}</td>
                                    <td>${student.last_name}</td>
                                    <td>${student.grade}</td>
                                    <td>${student.section}</td>
                                </tr>`;
            }
            console.log(htmlStudents);
            document.getElementById('students_list').innerHTML = htmlStudents;

        }

    });
</script>

@endsection
