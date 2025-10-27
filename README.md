# Task Manager

Este proyecto se creó con **Laravel 10** como backend y **Vue 3** para el frontend, utilizando **MySQL** como motor de base de datos y **SQLyog** para gestionarla.

Para levantar el proyecto en local es necesario tener instalado:

- **WAMP 3.3.5**  
- **Composer** de forma global (preferiblemente)  
- **Node.js** (última versión recomendada)  

Pasos para ejecutar el proyecto:

1. Clonar el repositorio completo.  
2. Abrir el proyecto con VS Code y en la terminal entrar a la carpeta del backend (`proyecto-api`) y ejecutar:  

```bash
php artisan serve
```

> OJO: Configurar en el archivo `.env` los puertos y los datos de conexión necesarios para que Laravel funcione correctamente.

3. Una vez levantada la API, abrir la carpeta del frontend (`task-manager`) y ejecutar:  

```bash
npm install
npm run dev
```

Esto levantará el frontend y te dará una URL donde podrás acceder al inicio de la aplicación web.
