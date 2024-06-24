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
        dayElement.className = 'text-center py-2 border cursor-pointer day';
        dayElement.innerText = day;
        dayElement.style.cursor = "pointer";

        // Check if this date is the current date
        const currentDate = new Date();
        if (year === currentDate.getFullYear() && month === currentDate.getMonth() && day === currentDate.getDate()) {
            dayElement.style.color = "#687575";
        }

        calendarElement.appendChild(dayElement);

        // Fetch data asynchronously
        fetchDataForDay(year, month, day, dayElement);
    }

    activeButtons()
}

function fetchDataForDay(year, month, day, dayElement, modal = false) {
    // Format the month and day with leading zeros if necessary
    const formattedMonth = String(month + 1).padStart(2, '0');
    const formattedDay = String(day).padStart(2, '0');
    const role = document.getElementById('role').dataset.value;

    const data = `${year}-${formattedMonth}-${formattedDay}`;

    const config = {
        method: 'GET',
        mode: 'cors',
        cache: 'no-cache',
    };

    const url = `/calendar/read?date=${data}&role=${role}`;

    fetch(url, config)
        .then(res => res.json())
        .then(result =>{
            if (result[0].length !== 0 && modal == false) {
                const currentDate = new Date();
                let currentYear = String(currentDate.getFullYear()).padStart(2, '0');
                let currentMonth = String(currentDate.getMonth() + 1).padStart(2, '0');
                let currentDay = String(currentDate.getDate()).padStart(2, '0');

                const actualDate = new Date(`${currentYear}-${currentMonth}-${currentDay}`);
                const date = new Date(result.date);

                if(date < actualDate){
                    dayElement.style.backgroundColor = "#fc9141"; // Update the day element with additional data
                }else{
                    dayElement.style.backgroundColor = "#ffffa2"; // Update the day element with additional data
                }
            }
            
            if(result.length !== 0 && modal == true){
                const role = document.getElementById('role').dataset.value;
                
                result[0].forEach((element)=>{
                    let role = document.getElementById('role').dataset.value;

                    // Code to update modal content with result data
                    const workElement = document.createElement('a');
                    workElement.style.boxShadow = "0px 13px 15px -13px rgba(153,153,153,1)";

                    if(role == 'teacher'){
                        // Aquí va la URL deseada
                        if(element.work_type_id in [1,2,3,4]){
                            workElement.setAttribute('href', `/teacher/work/edit?name=${element.slug}&id=${element.id}&mt=${element.slug}`);
                        }else{
                            workElement.setAttribute('href', `/teacher/work/edit?name=${element.slug}&id=${element.id}`);
                        }
                    }

                    if(role == 'student'){
                        // Aquí va la URL deseada
                        if(element.work_type_id == 5){
                            workElement.setAttribute('href', `/student/work/${element.slug}`);
                        }
                    }

                    const parrafo1 = document.createElement('p');
                    parrafo1.innerText = "Actividad: " + element.title;
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
                });

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
        })
        .catch(error => {
            throw(error)
        });

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
function showModal(selectedDate, date) {
    const modal = document.getElementById('myModal');
    const modalDateElement = document.getElementById('modalDate');

    fetchDataForDay(date.year, date.month, date.day, modalDateElement, true);
    
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
            const day = parseInt(dayElement.innerText);
            const selectedDate = new Date(currentYear, currentMonth, day);
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = selectedDate.toLocaleDateString(undefined, options);

            const date = {
                'day' : day,
                'month': currentMonth,
                'year' : currentYear,
            }

            showModal(formattedDate, date);
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