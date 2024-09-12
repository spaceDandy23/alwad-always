@extends('layouts.master')


@section('page_title', ' Verify')


@section('content')
<div class='card'>
    <div class='card-body'>
    @include('partials.alerts')
        <h1>{{ $subject->name ?? '' }} Attendance</h1>
        <h5 class='card-title'>Student Details</h5>
        <p class='card-text'>RFID Tag: {{ $student->tag->tag_number ?? '' }}</p>
        <p class='card-text'>First Name: {{ $student->first_name?? '' }}</p>
        <p class='card-text'>Last Name: {{ $student->last_name ?? '' }}</p>
        <p class='card-text'>Grade: {{ $student->grade ?? '' }}</p>
        <form action='{{ route('rfid-reader.verify', $subject->id ?? '') }}' method='post' id = 'hidden-form'>
            @csrf 
            <label for='rfid_tag' class='form-label'>RFID Tag</label>
            <input type='number' name='rfid_tag' class='form-control mb-2' id = 'rfid_field'>
            <button id = 'submit-btn' class='btn btn-primary'>Verify</button>
        </form>
    </div>
</div>









<script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('rfid_field').focus();
        });
</script>

@endsection