@extends('layouts.master')

@section('page_title', 'Subjects')

@section('content')

<div class="row justify-content-center">
    <div class="col-10">
        <div class="d-flex justify-content-center">
            <a href="#" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#createSubject">Add Subject</a>
        </div>
        <table class="table table-striped">
            <thead>
                @include('partials.alerts')
                <tr>
                    <th scope="col">Subject Schedule</th>
                    <th scope="col">Subject Name</th>
                    <th scope="col">Students</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $subject)
                    <tr>
                        <td>{{ $subject->schedule }}</td>
                        <td>{{ $subject->name }}</td>
                        <td>
                            <ul>
                                @foreach ($subject->students as $student)
                                    <li>{{$student->last_name}}, {{$student->first_name}}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <a class="btn btn-warning" href="#" data-bs-toggle="modal" data-bs-target="#editSubject{{ $subject->id }}">Edit</a>
                            <form action="{{ route('subjects.destroy', $subject->id) }}" method="post" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $subject->id }}">
                                    Delete
                                </button>
                            </form>
                            <!-- Edit Subject Modal -->
                            <div class="modal fade" id="editSubject{{ $subject->id }}" tabindex="-1" aria-labelledby="editSubjectLabel{{ $subject->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editSubjectLabel{{ $subject->id }}">Edit Subject</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('subjects.update', $subject->id) }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <label for="code_{{ $subject->id }}" class="form-label">Subject Schedule</label>
                                                <input type="text" class="form-control" id="code_{{ $subject->id }}" name="schedule" value="{{ $subject->schedule }}"  >
                                                <label for="name_{{ $subject->id }}" class="form-label">Subject Name</label>
                                                <input type="text" class="form-control" id="name_{{ $subject->id }}" name="name" value="{{ $subject->name }}"  >
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
                            <div class="modal fade" id="deleteModal{{ $subject->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $subject->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $subject->id }}">Confirm Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete "{{ $subject->name }}"?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('subjects.destroy', $subject->id) }}" method="post">
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
<!-- Create Subject Modal -->
<div class="modal fade" id="createSubject" tabindex="-1" aria-labelledby="createSubjectLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSubjectLabel">Add New Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('subjects.store') }}" method="post">
                    @csrf
                    <label for="schedule" class="form-label">Subject Schedule</label>
                    <input type="text" class="form-control" id="schedule" name="schedule">
                    <label for="name" class="form-label">Subject Name</label>
                    <input type="text" class="form-control" id="name" name="name">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
