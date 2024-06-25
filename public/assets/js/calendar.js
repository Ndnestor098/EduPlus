// Function to generate the calendar for a specific month and year
function generateCalendar(year, month) {
    const calendarElement = document.getElementById('calendar');
    const currentMonthElement = document.getElementById('currentMonth');
    
    // Create a date object for the first day of the specified month
    const firstDayOfMonth = new Date(year, month, 1);
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    
    // Clear the calendar
    calendarElement.innerHTML = '';

    // Set the current month text
    const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    currentMonthElement.innerText = `${monthNames[month]} ${year}`;
    
    // Calculate the day of the week for the first day of the month (0 - Sunday, 1 - Monday, ..., 6 - Saturday)
    const firstDayOfWeek = firstDayOfMonth.getDay();

    // Create headers for the days of the week
    const daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
    daysOfWeek.forEach(day => {
        const dayElement = document.createElement('div');
        dayElement.className = 'text-center font-semibold';
        dayElement.innerText = day;
        calendarElement.appendChild(dayElement);
    });

    // Create empty boxes for days before the first day of the month
    for (let i = 0; i < firstDayOfWeek; i++) {
        const emptyDayElement = document.createElement('div');
        calendarElement.appendChild(emptyDayElement);
    }

    // Create boxes for each day of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayElement = document.createElement('a');
        dayElement.className = 'text-center py-2 border cursor-pointer';
        dayElement.innerText = day;
        dayElement.style.cursor = "pointer";

        const formattedMonth = String(month + 1).padStart(2, '0');
        const formattedDay = String(day).padStart(2, '0');

        const data = `${year}-${formattedMonth}-${formattedDay}`;

        dayElement.id = data;

        // Check if this date is the current date
        const currentDate = new Date();
        if (year === currentDate.getFullYear() && month === currentDate.getMonth() && day === currentDate.getDate()) {
            dayElement.className += ' text-rojo';
        }

        calendarElement.appendChild(dayElement);

    }
    
    frontButton();

    activeButtons()
}

function frontButton(id = null, dayElement= null, modal = false) {
    const role = document.getElementById('role').dataset.value;

    const config = {
        method: 'GET',
        mode: 'cors',
        cache: 'no-cache',
    };

    const url = `/calendar/read?role=${role}`;
    
    fetch(url, config)
        .then(res => res.json())
        .then(result =>{
            const currentDate = new Date();
            let currentYear = String(currentDate.getFullYear()).padStart(2, '0');
            let currentMonth = String(currentDate.getMonth() + 1).padStart(2, '0');
            let currentDay = String(currentDate.getDate()).padStart(2, '0');

            const actualDate = new Date(`${currentYear}-${currentMonth}-${currentDay}`);

            if(modal == false){
                printFrontDate(result, actualDate);
            }
            
            if(modal == true){
                showInfoDate(result, dayElement);
            }
        })
        .catch(error => {
            throw(error)
        });

}

function printFrontDate(result, actualDate){
    result.works.forEach((element)=>{
        try {
            dayElement = document.getElementById(`${element.deliver}`);

            const date = new Date(element.deliver);

            if(date < actualDate){
                dayElement.style.backgroundColor = "#fc9141"; // Update the day element with additional data
            }else{
                dayElement.style.backgroundColor = "#ffffa2"; // Update the day element with additional data
            }
        } catch (error) {
            // ignorar error
        }
    });
}

function showInfoDate(result, dayElement){
    const role = document.getElementById('role').dataset.value;

    for (let index = 0; index < result.works.length; index++) {
        const element = result.works[index];
    
        if (element.deliver == id && result.works.length > 0) {
            // Code to update modal content with result data
            const workElement = document.createElement('a');
            workElement.style.boxShadow = "0px 13px 15px -13px rgba(153,153,153,1)";
    
            if (role == 'teacher') {
                // Aquí va la URL deseada
                if ([1, 2, 3, 4].includes(element.work_type_id)) {
                    workElement.setAttribute('href', `/teacher/work/edit?name=${element.slug}&id=${element.id}&mt=${element.slug}`);
                } else {
                    workElement.setAttribute('href', `/teacher/work/edit?name=${element.slug}&id=${element.id}`);
                }
            }
    
            if (role == 'student') {
                // Aquí va la URL deseada
                if (element.work_type_id == 5) {
                    workElement.setAttribute('href', `/student/work/${element.slug}`);
                }
            }
    
            const parrafo1 = document.createElement('p');
            parrafo1.innerText = "Actividad: " + limitarTexto(element.title, 35);
            parrafo1.style.paddingBottom = '3px';
            workElement.appendChild(parrafo1);
    
            const parrafo2 = document.createElement('p');
            parrafo2.innerText = "Materia: " + element.subject;
            parrafo2.style.paddingBottom = '3px';
            workElement.appendChild(parrafo2);
    
            const parrafo3 = document.createElement('p');
            parrafo3.innerText = "Curso o Año: " + element.course;
            parrafo3.style.paddingBottom = '3px';
            workElement.appendChild(parrafo3);
    
            const parrafo4 = document.createElement('p');
            parrafo4.innerText = "Fecha de Entrega: " + element.deliver;
            parrafo4.style.paddingBottom = '3px';
            workElement.appendChild(parrafo4);
    
            dayElement.appendChild(workElement);
    
            if ((index + 1) == 4) {
                break;
            }
        }
    }
    
    // Crear el contenido del <div> (enlaces y textos)
    const link = document.createElement('a');
    link.textContent = 'Ver Mas';
    link.className = 'text-verde font-bold text-center';

    if(role == 'student'){
        link.setAttribute('href', '/student/works'); // Aquí va la URL deseada
    }

    if(role == 'teacher'){
        link.setAttribute('href', '/teacher/works'); // Aquí va la URL deseada
    }

    dayElement.appendChild(link);
}


// Función para limitar el texto a un cierto número de caracteres
function limitarTexto(texto, limite) {
    if (texto.length <= limite) {
        return texto; // Retorna el texto completo si es menor o igual al límite
    } else {
        return texto.slice(0, limite) + '...'; // Retorna el texto recortado con puntos suspensivos al final
    }
}

// Initialize the calendar with the current month and year
const currentDate = new Date();
let currentYear = currentDate.getFullYear();
let currentMonth = currentDate.getMonth();
generateCalendar(currentYear, currentMonth);

// Event listeners for previous and next month buttons
document.getElementById('prevMonth').addEventListener('click', () => {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }

    generateCalendar(currentYear, currentMonth);
});

document.getElementById('nextMonth').addEventListener('click', () => {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }

    generateCalendar(currentYear, currentMonth);
});

// Function to show the modal with the selected date
function showModal(id) {
    const modal = document.getElementById('myModal');
    const modalDateElement = document.getElementById('modalDate');

    frontButton(id, modalDateElement, true);
    
    modal.classList.remove('hidden');
}

// Function to hide the modal
function hideModal() {
    const modal = document.getElementById('myModal');
    const modalDateElement = document.getElementById('modalDate');

    while (modalDateElement.firstChild) {
        modalDateElement.removeChild(modalDateElement.firstChild);
    }
    modal.classList.add('hidden');
}

function activeButtons(){
    // Event listener for date click events
    const dayElements = document.querySelectorAll('.cursor-pointer');

    dayElements.forEach(dayElement => {
        dayElement.addEventListener('click', () => {
            id = dayElement.id;
            showModal(id);
        });
    });
}

// Event listener for closing the modal
document.getElementById('closeModal').addEventListener('click', () => {
    hideModal();
});

document.getElementById('myModal').addEventListener('click', () => {
    hideModal();
});