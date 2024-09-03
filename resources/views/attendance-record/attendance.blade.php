@extends('layouts.master')

@section('page_title', 'Attendance Records')

@section('content')

<div class="row justify-content-center">
    <div class="col-10">
        <div class="d-flex justify-content-center">
            <a href="#" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#createAttendance">Add Attendance</a>
        </div>
        <table class="table table-striped">
            <thead>
                @include('partials.alerts')
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">RFID Tag</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Grade</th>
                    <th scope="col">Section</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->date }}</td>
                        <td>{{ $attendance->student->tag->tag_number ?? 'N/A' }}</td>
                        <td>{{ $attendance->student->first_name }}</td>
                        <td>{{ $attendance->student->last_name }}</td>
                        <td>{{ $attendance->student->grade }}</td>
                        <td>{{ $attendance->student->section }}</td>
                        <td>{{ $attendance->present ? 'Present' : 'Absent' }}</td>
                        <td>
                            <a class="btn btn-warning" href="#" data-bs-toggle="modal" data-bs-target="#editAttendance{{ $attendance->id }}">Edit</a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAttendanceModal{{ $attendance->id }}">
                                Delete
                            </button>

                            <!-- Edit Attendance Modal -->
                            <div class="modal fade" id="editAttendance{{ $attendance->id }}" tabindex="-1" aria-labelledby="editAttendanceLabel{{ $attendance->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editAttendanceLabel{{ $attendance->id }}">Edit Attendance</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('attendances.update', $attendance->id) }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <label for="date_{{ $attendance->id }}" class="form-label">Date</label>
                                                <input type="date" class="form-control" id="date_{{ $attendance->id }}" name="date" value="{{ $attendance->date }}" required>
                                                <label for="status_{{ $attendance->id }}" class="form-label">Status</label>
                                                <select class="form-select" id="status_{{ $attendance->id }}" name="present" required>
                                                    <option value="1" {{ $attendance->present ? 'selected' : '' }}>Present</option>
                                                    <option value="0" {{ !$attendance->present ? 'selected' : '' }}>Absent</option>
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

                            <!-- Delete Attendance Modal -->
                            <div class="modal fade" id="deleteAttendanceModal{{ $attendance->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteAttendanceModalLabel{{ $attendance->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteAttendanceModalLabel{{ $attendance->id }}">Confirm Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this attendance record for "{{ $attendance->student->first_name }} {{ $attendance->student->last_name }}" on {{ $attendance->date }}?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('attendances.destroy', $attendance->id) }}" method="post">
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

<!-- Create Attendance Modal -->
<div class="modal fade" id="createAttendance" tabindex="-1" aria-labelledby="createAttendanceLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAttendanceLabel">Add New Attendance Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('attendances.store') }}" method="post">
                    @csrf
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                    <label for="student_id" class="form-label">Student</label>
                    <select class="form-select" id="student_id" name="student_id" required>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }} (RFID: {{ $student->tag->tag_number ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="present" required>
                        <option value="1">Present</option>
                        <option value="0">Absent</option>
                    </select>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Attendance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
