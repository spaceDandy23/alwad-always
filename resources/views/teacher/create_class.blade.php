@extends('layouts.master')





@section('content')


@section('page_title', 'Create Class')
<div class="row"> 
    <div class="card col-6 px-0">
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
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Middle Name</th>
                    <th scope="col">Grade</th>
                    <th scope="col">Section</th>
                </thead>
                <tbody id="students_searched">



                </tbody>
            </table>
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


document.addEventListener('DOMContentLoaded', function() {

    let classData = {};
    getDefaultTimeSub();
    changeTitle();
    document.getElementById('start_hour').addEventListener('change', storeStartTime);
    document.getElementById('start_minute').addEventListener('change', storeStartTime);
    document.getElementById('start_ampm').addEventListener('change', storeStartTime);
    document.getElementById('end_hour').addEventListener('change', storeEndTime);
    document.getElementById('end_minute').addEventListener('change', storeEndTime);
    document.getElementById('end_ampm').addEventListener('change', storeEndTime);


    function changeTitle(){
        let selectElement = document.getElementById('subject');
        let selectedIndex = selectElement.selectedIndex;

        document.getElementById('subject_title').textContent = selectElement.options[selectedIndex].text;

        selectElement.addEventListener('change', function() {
        document.getElementById('subject_title').textContent = this.options[this.selectedIndex].text;
        classData['subject'] = this.value;
        });
    }



    function getDefaultTimeSub(){
        const startHour = document.getElementById('start_hour').value;
        const startMinute = document.getElementById('start_minute').value;
        const startAmpm = document.getElementById('start_ampm').value;

        classData['start_time'] = {
            hour: startHour,
            minute: startMinute,
            ampm: startAmpm
        };
        const endHour = document.getElementById('end_hour').value;
        const endMinute = document.getElementById('end_minute').value;
        const endAmpm = document.getElementById('end_ampm').value;

        classData['end_time'] = {
            hour: endHour,
            minute: endMinute,
            ampm: endAmpm
        };
        console.log(classData);
    }


    function storeStartTime() {
        const startHour = document.getElementById('start_hour').value;
        const startMinute = document.getElementById('start_minute').value;
        const startAmpm = document.getElementById('start_ampm').value;

        classData['start_time'] = {
            hour: startHour,
            minute: startMinute,
            ampm: startAmpm
        };

        console.log('Start Time:', classData['start_time']); 
    }

    function storeEndTime() {
        const endHour = document.getElementById('end_hour').value;
        const endMinute = document.getElementById('end_minute').value;
        const endAmpm = document.getElementById('end_ampm').value;

        classData['end_time'] = {
            hour: endHour,
            minute: endMinute,
            ampm: endAmpm
        };

        console.log('End Time:', classData['end_time']); 
    }

    document.getElementById('send_data').addEventListener('click', () =>{

        fetch("{{route('create-class')}}", {

            method: "POSt",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(classData)


        })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            if(data.success){
                console.log(data.success);
            }
            else{
                console.log(data.message);
            }
        })
        .catch((error) => {
            console.log(error);
        });
    

    });

    document.getElementById('search_button').addEventListener('click',()=>{

        let searchInput = document.getElementById('search_student').value;


        fetch("{{ route('search') }}", {

            method: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({search: searchInput})


        })
        .then((response) => {
            return response.json();
        })
        .then((data)=> {
            let studentData = '';
            if(data.success){
                
                Object.values(data.results).forEach((values, key) => {
                    studentData += `
                                    <tr data-key="${key}" class="student">
                                    <td>${values.first_name}</td>
                                    <td>${values.last_name}</td>
                                    <td>${values.middle_name}</td>
                                    <td>${values.grade}</td>
                                    <td>${values.section}</td>
                                    </tr>`;
                });
            }
            document.getElementById('students_searched').innerHTML = studentData;
            makeClickable(data.results);
        });
        function makeClickable(results){
            document.querySelectorAll('.student').forEach((value) => {
                value.addEventListener('click', function(){

                    let key = parseInt(this.getAttribute('data-key'),10);
                    let student = results[key];

                        if(!classData['students']){
                            classData['students'] = {}
                        }
                        classData['students'][key] = student;



                        console.log(classData);
                        generateclassData();
                });
            });
        }
        function removeStudent() {
                document.querySelectorAll('.student-added').forEach((value) => {
                    value.addEventListener('click', function() {
                        let key = parseInt(this.getAttribute('data-key'), 10);
                        delete classData['students'][key]; 

                        console.log('Removed student:', key);
                        generateclassData(); 
                    });
                });
            }
        function generateclassData(){
            let studentsAdded = ``;
            Object.keys(classData['students']).forEach((key) => {
            studentsAdded +=`
                        <tr data-key="${key}" class="student-added">
                        <td>${classData['students'][key].first_name}</td>
                        <td>${classData['students'][key].last_name}</td>
                        <td>${classData['students'][key].middle_name}</td>
                        <td>${classData['students'][key].grade}</td>
                        <td>${classData['students'][key].section}</td>
                        </tr>`;
            }); 
            document.getElementById('students_added').innerHTML= studentsAdded;
            removeStudent();
        }
    });
});

</script>

@endsection