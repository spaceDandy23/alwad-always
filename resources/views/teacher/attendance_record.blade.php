@extends('layouts.master')

@section('page_title', 'Class Attendance')

@section('content')

<div class="row justify-content-center">
    <div class="col-10">
        <table class="table table-striped">
            <div class="d-flex justify-content-center" id="modal_button">
               
            </div>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Grade</th>
                    <th scope="col">Section</th>
                    <th scope="col">Attendance</th>
                </tr>
            </thead>
            <tbody id="attendanceBody">
                
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="markAttendance" tabindex="-1" aria-labelledby="markAttendanceLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markAttendanceLabel">Mark Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <div id="studentFormList">

                    </div>
                    <div class="modal-footer">
                        <button id="send_student_data" class="btn btn-primary" data-bs-dismiss="modal">Submit Attendance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    let studentFormInputs = '';
    let students = '';
    let subjectIds = [];
    generateData();


    
    function generateData() {
        Object.entries(sessionStorage).forEach(([key, value]) => {
            const studentArray = JSON.parse(value);
            subjectIds.push(key);
            console.log(studentArray);
            Object.values(studentArray).forEach(studentData => {
                students += `
                    <tr class="student-data">
                        <td class="student_id" type="hidden">${studentData.id}</td>
                        <td class="student-name">${studentData.first_name} ${studentData.last_name}</td>
                        <td>${studentData.grade}</td>
                        <td>${studentData.section}</td>
                        <td>
                            <select class="form-select" name="present" data-student-id="${studentData.id}" data-subject-id="${key}">
                                <option value="1" ${studentData.present ? "selected" : ""}>Present</option>
                                <option value="0" ${!studentData.present ? "selected" : ""}>Absent</option>
                            </select>
                        </td>
                    </tr>
                `;
            });
        });
    }

    document.getElementById('attendanceBody').innerHTML = students;

    if (students) {
        document.getElementById('modal_button').innerHTML = ' <a href="#" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#markAttendance">Mark Attendance</a>';
    }
    document.querySelectorAll('.form-select').forEach(select => {
        select.addEventListener('change', function () {
            const studentId = this.getAttribute('data-student-id');
            const subjectId = this.getAttribute('data-subject-id');
            const presentValue = this.value;
            let sessionData = JSON.parse(sessionStorage.getItem(subjectId));
            sessionData[studentId].present = parseInt(presentValue);
            sessionStorage.setItem(subjectId, JSON.stringify(sessionData));



            console.log(`Updated session for Student ID: ${studentId}, Subject ID: ${subjectId}, Present: ${presentValue}`);
        });
    });

    document.getElementById('markAttendance').addEventListener('show.bs.modal', () => {
        studentFormInputs = '';
        document.querySelectorAll('.student-data').forEach((student) => {
            const studentName = student.querySelector('.student-name').innerText;
            const studentForm = student.querySelector('.form-select');

            const studentState = studentForm.options[studentForm.selectedIndex].innerText;
            console.log(` ${studentName} ${studentState} ${studentForm.value}`);

            studentFormInputs += `<ul>
                                    <li>
                                        ${studentName} - ${studentState}
                                    </li>
                                  </ul>`;
        });
        document.getElementById('studentFormList').innerHTML = studentFormInputs;
    });

    document.getElementById('send_student_data').addEventListener('click', () => {
        let url = "{{ route('store-attendance') }}";
        let presentStatuses = {};

        document.querySelectorAll('.student-data').forEach((student) => {
            const studentId = student.querySelector('.student_id').innerText;
            const studentForm = student.querySelector('.form-select');
            presentStatuses[studentId] = studentForm.value;
        });

        fetch(url, {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                subjects: subjectIds,
                present: presentStatuses
            }),
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                console.log('added');
            }
            else{
                console.log('student has 3 strikes');
            }
            });
        });
    });

</script>

@endsection
