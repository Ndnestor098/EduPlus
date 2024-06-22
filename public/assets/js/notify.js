// Función para limitar el texto a un cierto número de caracteres
function limitarTexto(texto, limite) {
    if (texto.length <= limite) {
        return texto; // Retorna el texto completo si es menor o igual al límite
    } else {
        return texto.slice(0, limite) + '...'; // Retorna el texto recortado con puntos suspensivos al final
    }
}

// Función asincrónica para realizar la solicitud fetch
async function fetchData() {
    // Configuración de la solicitud
    let config = {
        method: 'GET',
        mode: 'cors',
        cache: 'no-cache',
    };

    try {
        // Realiza la solicitud fetch de manera asíncrona
        let response = await fetch('/notifications/read', config);
        
        // Verifica el estado de la respuesta
        if (!response.ok) {
            throw new Error('No se pudo completar la solicitud.');
        }
        
        // Convierte la respuesta a JSON
        let data = await response.json();

        // Obtener el contenedor donde se va a agregar el elemento
        const container = document.getElementById('areaNotification');
        
        let countBucle = 0;
        let countNotify = 0;

        // Verificar si hay elementos en data
        if (data.length != 0) {
            // Procesa los datos obtenidos
            data.some(element => {
                // Verifica el tipo de notificación
                if (element.type.includes('StudentUpAssignment')) {
                    // Vista de Teacher
                    // Crear el elemento <div> con las clases y estilos
                    const divElement = document.createElement('div');
                    divElement.classList.add('custom-box');
                    divElement.classList.add('w-full');

                    // Crear el contenido del <div> (enlaces y textos)
                    const link = document.createElement('a');
                    link.setAttribute('href', 
                        '/teacher/correct/student/' + element.data.work.slug + '?work_id=' + element.data.work.work_id + '&notificationId=' + element.id); // Aquí va la URL deseada
                    link.style = 'width: 100%; display: block;';

                    const subjectSpan = document.createElement('span');
                    subjectSpan.textContent = 'Alumno: ' + element.data.work.name;
                    link.appendChild(subjectSpan);
                    link.appendChild(document.createElement('br'));

                    const subjectSpan2 = document.createElement('span');
                    subjectSpan2.textContent = 'Curso o Año: ' + element.data.work.course;
                    link.appendChild(subjectSpan2);

                    const dueDateSpan = document.createElement('span');
                    dueDateSpan.textContent = 'Entregado: ' + element.data.work.created_at.split('T')[0];
                    link.appendChild(document.createElement('br'));
                    link.appendChild(dueDateSpan);

                    // Agregar el enlace con su contenido al <div>
                    divElement.appendChild(link);

                    // Agregar el <div> al contenedor
                    container.appendChild(divElement);
                } else {
                    // Vista de Student
                    // Crear el elemento <div> con las clases y estilos
                    const divElement = document.createElement('div');
                    divElement.classList.add('custom-box');
                    divElement.classList.add('w-full');

                    // Crear el contenido del <div> (enlaces y textos)
                    const link = document.createElement('a');
                    link.setAttribute('href', '/student/work/' + element.data.work.slug + '?notificationId=' + element.id); // Aquí va la URL deseada

                    const titleSpan = document.createElement('span');
                    titleSpan.textContent = limitarTexto(element.data.work.title, 27);
                    link.appendChild(titleSpan);

                    const subjectSpan = document.createElement('span');
                    subjectSpan.textContent = 'Materia: ' + element.data.work.subject;
                    link.appendChild(document.createElement('br'));
                    link.appendChild(subjectSpan);

                    const dueDateSpan = document.createElement('span');
                    dueDateSpan.textContent = 'Entregar: ' + element.data.work.deliver;
                    link.appendChild(document.createElement('br'));
                    link.appendChild(dueDateSpan);

                    // Agregar el enlace con su contenido al <div>
                    divElement.appendChild(link);

                    // Agregar el <div> al contenedor
                    container.appendChild(divElement);
                }
                
                // Contadores de bucle y notificaciones
                countBucle += 1;
                countNotify += 1;
                document.getElementById('count').textContent = countNotify;
                
                // Modificar el color de la campanita
                let bell = document.getElementById('bell').classList.replace('text-verde', 'text-rojo');

                // Si countBucle alcanza 4, detener la iteración
                if (countBucle === 4) {
                    return true; // Esto detiene la iteración de some()
                }
            });
        } else {
            // No hay notificaciones, mostrar mensaje
            // Crear el elemento <div> con las clases y estilos
            const divElement = document.createElement('div');
            divElement.classList.add('w-full');
            divElement.classList.add('text-center');
            divElement.classList.add('text-rojo');
            divElement.classList.add('font-bold');

            // Crear el contenido del <div> (enlaces y textos)
            const link = document.createElement('a');
            link.textContent = 'No hay Notificaciones';
            link.setAttribute('href', '/notifications/show'); // Aquí va la URL deseada

            // Agregar el enlace con su contenido al <div>
            divElement.appendChild(link);
            
            // Agregar el <div> al contenedor
            container.appendChild(divElement);
        }

        // Crear el elemento <div> para ver más notificaciones
        const divElement = document.createElement('div');
        divElement.classList.add('w-full');
        divElement.classList.add('text-center');
        divElement.classList.add('text-verde');
        divElement.classList.add('font-bold');

        // Crear el contenido del <div> (enlaces y textos)
        const link = document.createElement('a');
        link.textContent = 'Ver Mas';
        link.setAttribute('href', '/notifications/show'); // Aquí va la URL deseada

        // Agregar el enlace con su contenido al <div>
        divElement.appendChild(link);
        
        // Agregar el <div> al contenedor
        container.appendChild(divElement);
        
    } catch (error) {
        // Manejar cualquier error que pueda ocurrir durante la solicitud
        console.error('Error al realizar la solicitud:', error);
    }
}

// Llamar a la función fetchData() para iniciar la solicitud de datos
fetchData();
