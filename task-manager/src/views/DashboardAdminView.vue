<template>
  <div class="p-6 min-h-screen bg-gray-900 text-gray-100">
   
    <!-- Navbar simple -->
    <nav class="bg-gray-800 text-gray-100 p-4 flex justify-between items-center">
      <h1 class="text-3xl font-bold mb-6">Panel de Administrador</h1>
      <button
        @click="logout"
        class="bg-red-600 hover:bg-red-500 px-3 py-1 rounded"
      >
        Logout
      </button>
    </nav>
    <br></br>
     
    <!-- Tabla de proyectos -->
    <div class="bg-gray-800/60 rounded-2xl p-4 mb-6 border border-gray-700 shadow-md overflow-x-auto">
      <h2 class="text-2xl font-semibold mb-4">Proyectos</h2>
      <div class="flex gap-2 mb-4">
  <button
    @click="goToCreateProject"
    class="bg-green-600 hover:bg-green-500 px-3 py-1 rounded transition-all duration-150"
  >
    Crear Proyecto
  </button>

  <button
    @click="$router.push({ name: 'taskFilter' })"
    class="bg-purple-600 hover:bg-purple-500 px-3 py-1 rounded transition-all duration-150"
  >
    Filtrar Tareas
  </button>
</div>
      <br></br>

      <table class="w-full text-left table-auto">
        <thead>
          <tr class="text-gray-400">
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Nombre</th>
            <th class="px-4 py-2">Descripción</th>
            <th class="px-4 py-2">Usuario</th>
            <th class="px-4 py-2">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="project in projects" :key="project.id" class="border-t border-gray-700 hover:bg-gray-700/50">
            <td class="px-4 py-2">{{ project.id }}</td>
            <td class="px-4 py-2">{{ project.name }}</td>
            <td class="px-4 py-2">{{ project.description }}</td>
            <td class="px-4 py-2">{{ project.user.name }}</td>
            <td class="px-4 py-2 space-x-2">
              <button
                @click="openProject(project.id)"
                class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1 rounded-xl font-medium shadow-md hover:shadow-lg"
              >
                Abrir
              </button>
              <button
                @click="editProject(project.id)"
                class="bg-yellow-500 hover:bg-yellow-400 text-white px-3 py-1 rounded-xl font-medium shadow-md hover:shadow-lg"
              >
                Editar
              </button>
              <button
                @click="deleteProject(project.id)"
                class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded-xl font-medium shadow-md hover:shadow-lg"
              >
                Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Lista de developers -->
    <div class="bg-gray-800/60 rounded-2xl p-4 border border-gray-700 shadow-md">
      <h2 class="text-2xl font-semibold mb-4">Desarrolladores</h2>
      <ul>
        <li v-for="dev in developers" :key="dev.id" class="mb-1">
          <span class="font-bold">{{ dev.name }}</span> - {{ dev.email }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useProjectStore } from '../stores/project'
import { useRouter } from 'vue-router'
import { useUserStore } from '../stores/user'
import axios from 'axios'

const projectStore = useProjectStore()
const userStore = useUserStore()
const router = useRouter()

const projects = computed(() => projectStore.projects)
const developers = computed(() => projectStore.developers)

onMounted(async () => {
  await projectStore.fetchAllProjects()
  await projectStore.fetchDevelopers()
})

const openProject = (projectId) => {
  router.push(`/projects/${projectId}`)
}

const editProject = (projectId) => {
  router.push({ name: 'projectForm', params: { id: projectId } })
}

const deleteProject = async (projectId) => {
  const confirmed = confirm(
    '⚠️ Atención: Se eliminará este proyecto y todas sus tareas relacionadas. ¿Deseas continuar?'
  )
  if (!confirmed) return

  try {
    await axios.delete(`http://127.0.0.1:8000/api/projects/${projectId}`, {
      headers: { Authorization: `Bearer ${userStore.token}` }
    })
    alert('✅ Proyecto y tareas eliminados correctamente')
    await projectStore.fetchAllProjects() // refresca la tabla
  } catch (err) {
    console.error('Error al eliminar proyecto:', err.response || err)
    alert('⚠️ No se pudo eliminar el proyecto')
  }
}

const logout = () => {
  userStore.logout()
  router.push({ name: 'home' })
}

const goToCreateProject = () => {
  router.push({ name: 'projectForm' })
}
</script>
