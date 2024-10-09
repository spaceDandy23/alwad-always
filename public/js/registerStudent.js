import { renderSearchedStudents } from './renderStudentSearched.js';
import { makeClickable } from './clickStudentForm.js';
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('form_register');
    form.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });




    document.getElementById('search_button').addEventListener('click',()=>{

        let searchInput = document.getElementById('search_student').value;


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
            renderSearchedStudents(data.results.data);
            makeClickable(data.results.data);
        });
    });



});