export function renderSearchedStudents(data){
    let studentData = ``;
    Object.values(data).forEach((values, key) => {
                studentData += `
                                <tr data-key="${key}" class="student">
                                <td>${values.first_name}</td>
                                <td>${values.last_name}</td>
                                <td>${values.middle_name}</td>
                                <td>${values.grade}</td>
                                <td>${values.section}</td>
                                </tr>`;
            });
    document.getElementById('students_searched').innerHTML = studentData;
}

