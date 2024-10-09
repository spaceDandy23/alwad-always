export function makeClickable(results){

    document.querySelectorAll('.student').forEach((value) => {
        value.addEventListener('click', function(){
            let student = results[parseInt(this.getAttribute('data-key'),10)];



            document.getElementById('student_id').value = student.id;
            document.getElementById('first_name').value = student.first_name;
            document.getElementById('last_name').value = student.last_name;
            document.getElementById('middle_name').value = student.middle_name;
            document.getElementById('section').value = student.section;
            document.getElementById('grade').value = student.grade;
        });

    });

}
