import { renderPaginatedLinks } from "./pagination.js";
import { renderSearchedStudents } from './renderStudentSearched.js';
import { makeClickable } from "./clickStudentForm.js";

document.addEventListener('DOMContentLoaded', function(){
    let searchButton = document.getElementById('search_button');


    if(searchButton){
        searchButton.addEventListener('click',()=>{

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
            .then((data) => {
                if(data.success){
                    renderSearchedStudents(data.results.data);
                    renderPaginatedLinks(data.results);
                    makeClickable(data.results.data);
                }

            });
        });
    }
    window.loadPage = function(url) {
        let searchInput = document.getElementById('search_student').value;
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
                clickStudent(data.results.data)
            }

        })
        .catch((error) => {
            console.error(error);
        });
    };

});
