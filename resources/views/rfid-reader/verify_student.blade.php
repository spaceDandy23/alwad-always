@extends('layouts.master')

@section('page_title', 'Verify')

@section('content')
<div class='card'>
    <div class='card-body'>

        <h1 id="subject_name">{{ $subject->name ?? '' }}</h1>

        <h5 class='card-title'>Student Details</h5>
        <p class='card-text'>RFID Tag: <span id="rfid_tag_value"></span></p>
        <p class='card-text'>First Name: <span id="first_name_value"></span></p>
        <p class='card-text'>Last Name: <span id="last_name_value"></span></p>
        <p class='card-text'>Grade: <span id="grade_value"></span></p>

        <form id='tag_form'>
            @csrf
            <label for='rfid_tag' class='form-label'>RFID Tag</label>
            <input type='number' name='rfid_tag' class='form-control mb-2' id='rfid_field'>
            <input type="hidden" value="{{ $subject->id }}" id="subject_id">
            <button id='submit-btn' class='btn btn-primary' type='submit'>Verify</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('rfid_field').focus();

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
                    document.getElementById('rfid_tag_value').innerText = data.student.rfid_tag;
                    document.getElementById('first_name_value').innerText = data.student.first_name;
                    document.getElementById('last_name_value').innerText = data.student.last_name;
                    document.getElementById('grade_value').innerText = data.student.grade;
                } else {
                    console.error('Student not found or other error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
</script>

@endsection
