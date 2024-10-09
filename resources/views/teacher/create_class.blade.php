@extends('layouts.master')





@section('content')


@section('page_title', 'Create Class')
<div class="row"> 
    <div class="card col-6 px-0">
        <div id="alert">
            
        </div>
        <div class="card-header text-center">
            <h3>Create Class</h3>
        </div>
        <div class="card-body">
            @include('partials.alerts')

            <div class="border border-dark rounded p-2 mt-4">
                <div class="row align-items-center">
                    <div class="col">
                        <label for="search_student" class="form-label">Filter Students</label>
                    </div>
                    <div class="col-8">
                        <input type="text" name="search_student" id="search_student" class="form-control" placeholder="Enter Either Name, Grade, or Section">
                    </div>
                    <div class="col">
                        <button id="search_button" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </div>
                <label for="subjects" class="form-label">Subject</label>
                <select name="subject" id="subject" class="form-select">
                    <option value="" selected>Select Class</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
                    <label for="section" class="form-label">Schedule</label>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="start_time" class="form-label">Start Time</label>
                            <div class="input-group">
                                <select name="start_hour" id="start_hour" class="form-select">
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ sprintf('%02d', $i) }}</option>
                                    @endfor
                                </select>
                                <span class="input-group-text">:</span>
                                <select name="start_minute" id="start_minute" class="form-select">
                                    @for($i = 0; $i < 60; $i += 5)
                                        <option value="{{ $i }}">{{ sprintf('%02d', $i) }}</option>
                                    @endfor
                                </select>
                                <select name="start_ampm" id="start_ampm" class="form-select">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <label for="end_time" class="form-label">End Time</label>
                            <div class="input-group">
                                <select name="end_hour" id="end_hour" class="form-select">
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ sprintf('%02d', $i) }}</option>
                                    @endfor
                                </select>
                                <span class="input-group-text">:</span>
                                <select name="end_minute" id="end_minute" class="form-select">
                                    @for($i = 0; $i < 60; $i += 5)
                                        <option value="{{ $i }}">{{ sprintf('%02d', $i) }}</option>
                                    @endfor
                                </select>
                                <select name="end_ampm" id="end_ampm" class="form-select">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <button id="send_data" class="btn btn-primary">Submit</button>


            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Middle Name</th>
                        <th scope="col">Grade</th>
                        <th scope="col">Section</th>
                    </tr>
                </thead>
                <tbody id="students_searched">



                </tbody>
            </table>
            <div class="row justify-content-center">
                <div class="col-12 mb-3 text-center">
                    <p class="small text-muted" id="pagination_caption">

                    </p>
                </div>
                
                <div class="col-12">
                    <ul id="pagination" class="pagination justify-content-center mb-0">

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="text-center">
            <h1 id="subject_title"></h1>
        </div>
        <table class="table table-bordered">
            <thead>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Middle Name</th>
                <th scope="col">Grade</th>
                <th scope="col">Section</th>
            </thead>
            <tbody id="students_added">



            </tbody>


        </table>

    </div>
</div>
<script>
    const routes = {
        search: "{{ route('search') }}",
        createClass: "{{ route('create-class') }}",
        csrfToken: "{{ csrf_token() }}",
    
    };
</script>

<script type="module" src="{{ asset('js/createClass.js') }}">




</script>

@endsection