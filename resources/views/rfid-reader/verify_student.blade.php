@extends('layouts.master')

@section('page_title', 'RFID-Reader')

@section('content')



@if (Session::has('subject'))
    <div class='card'>
        <div class='card-body' id="card_body">
            <div id="alert"></div>
            <h1 id="subject_name">{{ Session::get('subject')->name }}</h1>

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
                <input type="hidden" value="{{ Session::get('subject')->id }}" id="subject_id" name="subject_id">
                <button class='btn btn-primary' type='submit'>Verify</button>
            </form>

            <table class="table table-striped">
                <thead>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Grade</th>
                    <th scope="col">Section</th>
                    <th scope="col">Present</th>
                </thead>
                <tbody id="students_list">
                </tbody>
            </table>
        </div>
    </div>
@else
    <div>
        <h1>You haven't selected a subject. Go back?</h1>
        <a href="{{ route('class.index') }}" class="btn btn-primary">Click here</a>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('rfid_field').focus();

        let div = document.getElementById('alert');
        const subSessionId = "{{ $subSession->id }}"; 


        if(sessionStorage.getItem(subSessionId)){
            console.log('true');
        }
        else{
            sessionStorage.setItem("{{$subSession->id}}", JSON.stringify(@json($subSessionStudents)));
        }
        loadStoredStudents();

        document.getElementById('tag_form').addEventListener('submit', (event) => {
            event.preventDefault();
            let formData = new FormData(document.getElementById('tag_form'));
            const url = "{{ route('rfid-reader.verify') }}";


            
            if(!checkIfExist(formData)){
                alert({}, 'Student Already Attended');
                return;
            }

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
                    generateData(data.studentsAttended);
                    sessionStorage.setItem(subSessionId, JSON.stringify(data.studentsAttended));
                } else {
                    alert(data);
                    clearTextContent();
                    console.error('Student not found or other error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        function alert(data, message = '') {
            if(message) {
                div.className = 'alert alert-warning';
                div.innerHTML = `<li>${message}</li>`;
            } else if(data.success) {
                div.className = 'alert alert-success';
                div.innerHTML = `<li>${data.message}</li>`;
            } else {
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
            document.getElementById('section').innerText = '';
        }

        function generateData(students) {
            let studentsData = '';
            console.log(students);
            Object.values(students).forEach(student => {
                studentsData += `
                <tr>
                    <td>${student.first_name}</td>
                    <td>${student.last_name}</td>
                    <td>${student.grade}</td>
                    <td>${student.section}</td>
                    <td>${student.present ? 'Present' : 'Pending'}</td>
                </tr>
            `;
                
            });

            document.getElementById('students_list').innerHTML = studentsData;
        }

        function loadStoredStudents() {
            const storedStudents = sessionStorage.getItem(subSessionId);
            if (storedStudents) {
                const students = JSON.parse(storedStudents);
                generateData(students);
            }
        }

        function checkIfExist(formData) {
            for (const values of Object.values(JSON.parse(sessionStorage.getItem(subSessionId)))) {
                if (values.rfid_tag === formData.get('rfid_tag')) {
                    if(values.present){
                        return false;
                    }
                }
            }
            return true;
        }
    });
</script>


@endsection
