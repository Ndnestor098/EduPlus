"use strict";
const course = document.getElementById('course');
const qualification = document.getElementById('qualification');
const csrf = document.getElementById('csrf');
const subject = document.getElementById('subject');

course.addEventListener('change', (e)=>{
    const selectedValue = e.target.value;
    const selectedSubject = subject.value;

    //Eliminar y Agregar Los valores por Default
    while (qualification.firstChild) {
        qualification.removeChild(qualification.firstChild);
    }
    let option = document.createElement('option');
    option.textContent = 'Selecciona el metodo calificativo:';
    option.disabled = true;
    option.selected = true;
    qualification.appendChild(option);
    
    // Crear un nuevo FormData
    let formData = new FormData();

    formData.append('course', selectedValue);
    formData.append('subject', selectedSubject);

    // Configurar encabezados, incluyendo el token CSRF
    let headers = new Headers({
        'X-CSRF-TOKEN': `${csrf.value}`
    });

    // Configuración de la solicitud
    let config = {
        method: 'POST',
        headers: headers,
        mode: 'cors',
        cache: 'no-cache',
        body: formData
    };

    // Realizar la solicitud fetch
    fetch('/teacher/work/add', config)
        .then(response => response.json())
        .then(data => {
            data.forEach(element => {
                let option = document.createElement('option');
                option.value = element.work_type.name;
                option.textContent = element.work_type.name;
                qualification.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
})