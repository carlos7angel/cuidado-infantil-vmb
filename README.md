# Cuidado Infantil VMB

Sistema integral de gestión y monitoreo para centros de cuidado infantil, desarrollado sobre el framework **Apiato** (PHP/Laravel).

## 🚀 Acerca del Proyecto

Esta plataforma permite la administración eficiente de centros infantiles, el seguimiento del desarrollo de los niños, y la gestión operativa del personal y las instalaciones. Está diseñado para servir a dos interfaces principales:

1.  **Panel Administrativo (Web):** Para administradores y coordinadores.
2.  **Aplicación Móvil (API):** Para educadores y padres (en desarrollo).

## 🏗 Arquitectura

El proyecto utiliza **Apiato**, implementando el patrón arquitectónico **Porto SAP** (Software Architectural Pattern).

### Contenedores Principales (Monitoring)

El núcleo del negocio reside en la sección `Monitoring`, la cual ha sido recientemente refactorizada y optimizada:

*   **Child (Infantes):** Gestión centralizada de perfiles, historias médicas y sociales.
*   **ChildcareCenter (Centros):** Administración de múltiples centros infantiles.
*   **ChildEnrollment (Inscripciones):** Historial de inscripciones y asignación a salas.
*   **Room (Salas):** Gestión de grupos y capacidades.
*   **Educator (Educadores):** Gestión de personal docente y asignaciones.
*   **Attendance (Asistencia):** Registro diario de asistencia.

### Módulos de Salud y Desarrollo

*   **ChildDevelopment:** Evaluaciones de desarrollo infantil (basado en hitos).
*   **NutritionalAssessment:** Seguimiento antropométrico (Peso/Talla) con estándares de la OMS.
*   **ChildVaccination:** Seguimiento del esquema de vacunación y dosis pendientes.

## 🛠 Optimizaciones Recientes (Refactoring)

Se ha realizado una limpieza exhaustiva y optimización de los contenedores para reducir la deuda técnica y mejorar la mantenibilidad:

1.  **Limpieza de API (Dead Code Removal):**
    *   Se eliminaron endpoints CRUD autogenerados (Create/Update/Delete/List) que no eran consumidos por la App Móvil.
    *   Se preservaron únicamente los endpoints críticos y específicos requeridos por la App Móvil (e.g., `GetChildVaccinationTracking`).

2.  **Centralización de Lógica de Negocio:**
    *   El Panel Administrativo (`Frontend/Administrator`) consume directamente las **Tasks** y **Actions** de los contenedores de `Monitoring`, eliminando la necesidad de controladores API redundantes.
    *   Se eliminaron las carpetas `UI/WEB` dentro de los contenedores de `Monitoring` que duplicaban lógica ya existente en el módulo `Administrator`.

3.  **Seguridad y Estándares:**
    *   **Hashids:** Implementación de ofuscación de IDs en todos los endpoints públicos/móviles.
    *   **Middleware:** Validación estricta de `auth:api` para rutas móviles y `auth:web` para el panel administrativo.

## 💻 Requisitos Técnicos

*   PHP >= 8.2
*   Laravel >= 10.x
*   Apiato >= 12.x
*   MySQL >= 8.0

## 📝 Instalación

1.  Clonar el repositorio.
2.  Instalar dependencias: `composer install`
3.  Configurar entorno: `cp .env.example .env`
4.  Generar key: `php artisan key:generate`
5.  Migrar base de datos: `php artisan migrate --seed`

## 👥 Contribución

Este proyecto sigue los estándares de codificación de Apiato y Laravel. Antes de crear un Pull Request, asegúrese de que su código pase los linters y pruebas existentes.
