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
                <form action="{{ route('rfid-reader-subject.index', $subjectId) }}" method="post">
                    @csrf
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
                        <th scope="col">Actions</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['students'] as $student)
                        <tr>
                            <td>{{ $student->first_name }}</td>
                            <td>{{ $student->last_name }}</td>
                            <td>{{ $student->grade }}</td>
                            <td>{{ $student->section }}</td>
                            <td>
                                <a class="btn btn-warning" href="#" data-bs-toggle="modal" data-bs-target="#editStudent{{ $student->id }}_{{ $subjectId }}">Edit</a>

                                <!-- Edit Student Modal -->
                                <div class="modal fade" id="editStudent{{ $student->id }}_{{ $subjectId }}" tabindex="-1" aria-labelledby="editStudentLabel{{ $student->id }}_{{ $subjectId }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editStudentLabel{{ $student->id }}_{{ $subjectId }}">Edit Student Status for {{ $data['subject']->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('class.update', $student->pivot->id) }}" method="post">
                                                    @csrf
                                                    @method('PUT')
                                                    <label for="present_{{ $subjectId }}" class="form-label">Present for {{ $data['subject']->name }}</label>
                                                    <select class="form-select" id="present_{{ $subjectId }}" name="present">
                                                        <option value="1" {{ $student->pivot->present ? 'selected' : '' }}>Yes</option>
                                                        <option value="0" {{ !$student->pivot->present ? 'selected' : '' }}>No</option>
                                                    </select>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $student->pivot->present ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
</div>

@endsection
