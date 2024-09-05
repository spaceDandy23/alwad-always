@extends('layouts.master')

@section('page_title', 'Attendance Records')

@section('content')

<div class="row justify-content-center">
    <div class="col-10">
        <table class="table table-striped">
            <thead>
                @include('partials.alerts')
                <tr>
                    <th scope="col">First Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $attendance)
                    <tr>
                        <td>
                            {{$attendance->subject->schedule}}
                        </td>
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
                                            Are you sure you want to delete this attendance record for "
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

@endsection
