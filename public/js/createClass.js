import {renderPaginatedLinks} from './pagination.js';
import { renderSearchedStudents } from './renderStudentSearched.js';
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

        if (!selectElement) {
            console.error('Subject select element not found!');
            return;
        }
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

        fetch(routes.createClass, {

            method: "POST",
            headers: {
                'X-CSRF-TOKEN': routes.csrfToken,
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

    let searchInput = document.getElementById('search_student').value;
    document.getElementById('search_button').addEventListener('click',()=>{
        searchInput = document.getElementById('search_student').value;

        fetch(routes.search, {

            method: "POST",
            headers: {
                'X-CSRF-TOKEN': routes.csrfToken,
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
            console.log(data.results);
                if(data.success){
                    renderSearchedStudents(data.results.data);
                    renderPaginatedLinks(data.results, searchInput);
                    makeClickable(data.results.data);
                }

        });

    });
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
    window.loadPage = function(url) {
        fetch(url, {
            method: 'POST', 
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': routes.csrfToken
            },
            body: JSON.stringify({ search: searchInput })
        })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            if(data.success){
                console.log(data.results);
                renderSearchedStudents(data.results.data);
                renderPaginatedLinks(data.results, searchInput);
                makeClickable(data.results.data);
            }

        })
        .catch((error) => {
            console.error(error);
        });
    };
});