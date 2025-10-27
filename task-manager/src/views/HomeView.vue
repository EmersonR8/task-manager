<template>
  <div class="min-h-screen bg-gray-900 text-gray-100 flex flex-col items-center justify-center px-4">
    <!-- Contenedor principal -->
    <div class="bg-gray-800/60 backdrop-blur-md shadow-lg rounded-2xl p-10 w-full max-w-lg text-center border border-gray-700">
      <h1 class="text-4xl font-extrabold mb-4 tracking-tight">
        Bienvenido a la app de gestion de proyectos.
      </h1>
      <p class="text-gray-400 mb-8 text-sm">
        Gestiona tus datos, usuarios y procesos desde un entorno moderno y elegante.
      </p>

      <!-- Botón de acción -->
      <router-link
        to="/login"
        class="inline-block bg-indigo-600 hover:bg-indigo-500 transition-colors text-white px-6 py-3 rounded-xl font-medium shadow-md hover:shadow-lg"
      >
        Ir al Login
      </router-link>

      <!-- footer -->
      <div class="mt-8 text-xs text-gray-500">
        <p>&copy; {{ new Date().getFullYear() }} Gestion de proyectos · Todos los derechos reservados</p>
      </div>
    </div>
  </div>
</template>
