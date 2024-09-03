@extends('layouts.master')

@section('page_title', 'Students')

@section('content')

<div class="row justify-content-center">
    <div class="col-10">
        <div class="d-flex justify-content-center">
            <a href="#" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#createStudent">Add Student</a>
        </div>
        <table class="table table-striped">
            <thead>
                @include('partials.alerts')
                <tr>
                    <th scope="col">RFID tag</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Middle Name</th>
                    <th scope="col">Grade</th>
                    <th scope="col">Section</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->tag->tag_number }}</td>
                        <td>{{ $student->first_name }}</td>
                        <td>{{ $student->last_name }}</td>
                        <td>{{ $student->middle_name }}</td>
                        <td>{{ $student->grade }}</td>
                        <td>{{ $student->section }}</td>
                        <td>
                            <a class="btn btn-warning" href="#" data-bs-toggle="modal" data-bs-target="#editStudent{{ $student->id }}">Edit</a>
                            <form action="{{ route('students.destroy', $student->id) }}" method="post" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $student->id }}">
                                    Delete
                                </button>
                            </form>
                            <!-- Edit Student Modal -->
                            <div class="modal fade" id="editStudent{{ $student->id }}" tabindex="-1" aria-labelledby="editStudentLabel{{ $student->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editStudentLabel{{ $student->id }}">Edit Student</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('students.update', $student->id) }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <label for="rfid_tag_{{ $student->id }}" class="form-label">RFID Tag</label>
                                                <input type="text" class="form-control" id="rfid_tag_{{ $student->id }}" name="rfid_tag" value="{{ $student->tag->tag_number }}" required>
                                                <label for="first_name_{{ $student->id }}" class="form-label">First Name</label>
                                                <input type="text" class="form-control" id="first_name_{{ $student->id }}" name="first_name" value="{{ $student->first_name }}" required>
                                                <label for="last_name_{{ $student->id }}" class="form-label">Last Name</label>
                                                <input type="text" class="form-control" id="last_name_{{ $student->id }}" name="last_name" value="{{ $student->last_name }}" required>
                                                <label for="middle_name_{{ $student->id }}" class="form-label">Middle Name</label>
                                                <input type="text" class="form-control" id="middle_name_{{ $student->id }}" name="middle_name" value="{{ $student->middle_name }}">
                                                <label for="grade_{{ $student->id }}" class="form-label">Grade</label>
                                                <select class="form-select" id="grade_{{ $student->id }}" name="grade" required>
                                                    @for($i = 7; $i <= 10; $i++)
                                                        <option value="{{ $i }}" {{ $student->grade == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                                <label for="section_{{ $student->id }}" class="form-label">Section</label>
                                                <select class="form-select" id="section_{{ $student->id }}" name="section" required>
                                                    @for($i = 1; $i <= 3; $i++)
                                                        <option value="{{ $i }}" {{ $student->section == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                    @endfor
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
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $student->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $student->id }}">Confirm Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete "{{ $student->first_name }} {{ $student->last_name }}"?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('students.destroy', $student->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Create Student Modal -->
<div class="modal fade" id="createStudent" tabindex="-1" aria-labelledby="createStudentLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createStudentLabel">Add New Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('students.store') }}" method="post">
                    @csrf
                    <label for="rfid_tag" class="form-label">RFID Tag</label>
                    <input type="text" class="form-control" id="rfid_tag" name="rfid_tag">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name">
                    <label for="middle_name" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="middle_name" name="middle_name">
                    <label for="grade" class="form-label">Grade</label>
                    <select class="form-select" id="grade" name="grade">
                        @for($i = 7; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    <label for="section" class="form-label">Section</label>
                    <select class="form-select" id="section" name="section">
                        @for($i = 1; $i <= 3; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
