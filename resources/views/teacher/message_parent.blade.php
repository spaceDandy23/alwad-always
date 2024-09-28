@extends('layouts.master')

@section('page_title', 'Students With Strikes')

@section('content')

<div class="row justify-content-center">
    <div class="col-10">
        <table class="table table-striped">
            <thead>
                @include('partials.alerts')
                <tr>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Grade</th>
                    <th scope="col">Section</th>
                    <th scope="col">Number of Strikes</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($studentsWithStrikes as $student)
                    <tr>
                        <td>{{ $student->first_name }}</td>
                        <td>{{ $student->last_name }}</td>
                        <td>{{ $student->grade }}</td>
                        <td>{{ $student->section }}</td>
                        <td>{{ $student->strikes }}</td>
                        <td><a href="" class="btn btn-primary" data-name="{{$student->first_name}} {{$student->last_name }}"> Message </a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () =>{

        document.querySelectorAll('.btn').forEach((value,index) => {

            value.addEventListener('click', function() {
                alert(`${this.getAttribute('data-name')} modal message parent`);
                console.log(value.textContent);
            });


        });





    });



</script>

@endsection
