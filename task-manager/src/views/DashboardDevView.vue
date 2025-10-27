<template>
  <div class="p-6 min-h-screen bg-gray-900 text-gray-100">
    
    <!-- Navbar simple -->
    <nav class="bg-gray-800 text-gray-100 p-4 flex justify-between items-center">
      <span class="font-bold text-lg">Mis Proyectos</span>
      <button
        @click="logout"
        class="bg-red-600 hover:bg-red-500 px-3 py-1 rounded"
      >
        Logout
      </button>
    </nav>
    <br></br>
    <div v-if="userProjects.length === 0" class="text-gray-400">
      No tienes proyectos asignados.
    </div>

    <div v-for="project in userProjects" :key="project.id" class="bg-gray-800/60 p-6 rounded-2xl mb-4 shadow-md border border-gray-700">
      <h2 class="text-xl font-semibold mb-2">{{ project.name }}</h2>
      <p class="text-gray-400 mb-4">{{ project.description }}</p>
      <p class="text-sm text-gray-400 mb-4">Progreso: {{ Number(project.progress).toFixed(0) }}%</p>


      <div class="flex gap-4 mb-4">
        <div class="bg-blue-600/30 p-2 rounded-md flex-1 text-center">
          <span class="font-bold">{{ countTasks(project, 'pendiente') }}</span> Pendientes
        </div>
        <div class="bg-yellow-500/30 p-2 rounded-md flex-1 text-center">
          <span class="font-bold">{{ countTasks(project, 'en_progreso') }}</span> En progreso
        </div>
        <div class="bg-green-600/30 p-2 rounded-md flex-1 text-center">
          <span class="font-bold">{{ countTasks(project, 'completada') }}</span> Completadas
        </div>
      </div>

      <div>
        <h3 class="font-semibold mb-2">Tareas:</h3>
        <ul>
          <li v-for="task in project.tasks" :key="task.id" class="mb-2">
            <span class="font-bold">{{ task.title }}</span> - <span>{{ task.status }}</span>
            <p class="text-gray-400 ml-2">{{ task.description }}</p> <!-- Agregado -->
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useProjectStore } from '../stores/project'
import { useUserStore } from '../stores/user'
import { useRouter } from 'vue-router'

const userStore = useUserStore()
const router = useRouter()

const projectStore = useProjectStore()

// Computed para que sea reactivo
const userProjects = computed(() => projectStore.userProjects)

// onMounted debe ser async
onMounted(async () => {
   await projectStore.fetchUserProjects()
})

// Función para contar tareas por estado
const countTasks = (project, status) => {
  if (!project.tasks) return 0
  return project.tasks.filter(task => task.status === status).length
}


const logout = () => {
  userStore.logout() // limpia token y user
  router.push({ name: 'home' }) // redirige a HomeView
}
</script>
