@extends('layouts.master')

@section('page_title', 'Class Attendance')

@section('content')



<h1 id="test"></h1>








<script>



document.addEventListener('DOMContentLoaded', function() {
    let students = '';
    

    Object.entries(sessionStorage).forEach(([key, value]) => {
        const studentArray = JSON.parse(value);
            studentArray.forEach(studentData => {
                    students += `<li>${studentData.first_name}</li>`;
            });
    });
    
    document.getElementById('test').innerHTML = students;
});
</script>


@endsection