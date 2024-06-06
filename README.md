<p align="center"><a href="https://github.com/Ndnestor098/ScarpetossLaravel" target="_blank"><img src="https://ndnestor098.github.io/WebCV/img/logoEduPlus.png" width="400" alt="EduPlus Logo"></a></p>


# EduPlus

EduPlus es una plataforma educativa diseñada para facilitar la gestión de calificaciones y tareas entre estudiantes y profesores. La aplicación permite a los profesores asignar trabajos y evaluar a los estudiantes de manera eficiente, y proporciona control administrativo para directores.

## Características

- **Gestión de usuarios:**
  - Creación, edición y eliminación de estudiantes y profesores.
  - Gestión de permisos y roles (estudiantes, profesores, directores).
- **Asignación y calificación de tareas:**
  - Los profesores pueden asignar y calificar tareas, proyectos, exámenes, exposiciones, etc.
  - Los estudiantes pueden ver sus trabajos asignados y calificaciones.
- **Visualización de calificaciones y reportes:**
  - Los estudiantes pueden ver sus calificaciones detalladas por materia.
  - Los profesores pueden generar reportes de calificaciones.
  - Los directores pueden visualizar las calificaciones de todos los estudiantes.
- **Control administrativo:**
  - Los directores pueden modificar y gestionar los registros de estudiantes y profesores (nombre, email, contraseña, etc..).
  - Acceso a reportes globales de calificaciones.

## Tecnologías Utilizadas

- **Backend:** Laravel 11.x
- **Frontend:** Blade, Tailwind CSS
- **Base de Datos:** MySQL
- **Autenticación:** Laravel Breeze
- **Versionado:** Git

## Instalación

Sigue estos pasos para instalar y configurar EduPlus en tu entorno local:

1. Clona el repositorio:
    ```sh
    git clone https://github.com/Ndnestor098/EduPlus.git
    cd EduPlus
    ```

2. Instala las dependencias de PHP:
    ```sh
    composer install
    ```

3. Instala las dependencias de JavaScript:
    ```sh
    npm install
    npm run dev
    ```

4. Instala las dependencias de Idiomas de Laravel:
    ```sh
    composer require --dev laravel-lang/common
    ```

5. Configura el archivo `.env`:
    ```sh
    cp .env.example .env
    php artisan key:generate
    ```

6. Configura la base de datos en el archivo `.env` y luego ejecuta las migraciones:
    ```sh
    php artisan migrate --seed
    ```

7. Inicia el servidor de desarrollo:
    ```sh
    php artisan serve
    ```

## Uso

### Roles y Permisos

- **Profesor:** Puede asignar trabajos, calificar a los estudiantes y ver reportes de calificaciones.
- **Estudiante:** Puede ver los trabajos asignados, sus calificaciones y subir sus tareas.
- **Director:** Tiene acceso administrativo para visualizar las calificaciones de los estudiantes y gestionar usuarios (estudiantes y profesores).

### Funcionalidades Principales

1. **Asignación de Trabajos:**
    - Los profesores pueden asignar diferentes tipos de trabajos (tareas, proyectos, exámenes, exposiciones, etc.).
    - Los trabajos pueden ser filtrados por curso y materia.

2. **Calificación de Estudiantes:**
    - Los profesores pueden calificar los trabajos de los estudiantes y subir calificaciones.
    - Las calificaciones pueden ser vistas por los estudiantes en su panel de usuario.

3. **Visualización de Calificaciones:**
    - Los estudiantes pueden ver sus calificaciones detalladas en cada materia.
    - Los profesores pueden generar reportes de calificaciones.
    - Los directores pueden visualizar las calificaciones de todos los estudiantes.

4. **Control Administrativo:**
    - Los directores pueden modificar y gestionar los registros de estudiantes y profesores.
    - Acceso a reportes globales de calificaciones.

## Contribución

Si deseas contribuir a EduPlus, por favor sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una rama para tu nueva funcionalidad (`git checkout -b feature/nueva-funcionalidad`).
3. Realiza tus cambios y haz commit (`git commit -am 'Añadir nueva funcionalidad'`).
4. Empuja tu rama (`git push origin feature/nueva-funcionalidad`).
5. Crea un Pull Request.

## Licencia

Este proyecto está licenciado bajo la Apache License 2.0. Consulta el archivo [LICENSE](LICENSE) para más detalles.

## Contacto

Si tienes alguna pregunta o sugerencia, por favor contacta a [tu_nombre](mailto:trabajo.nestor.098@gmail.com).

---

¡Gracias por usar EduPlus!
