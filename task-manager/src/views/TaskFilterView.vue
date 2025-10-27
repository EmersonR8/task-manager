<template>
  <div class="p-6 min-h-screen bg-gray-900 text-gray-100">
    <h1 class="text-3xl font-bold mb-6">Filtrar Tareas</h1>
    <button
    @click="router.back()"
    class="mb-4 bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-xl"
    >
    ← Volver
    </button>
    <div class="bg-gray-800/60 p-4 rounded-2xl border border-gray-700 mb-6 flex flex-wrap items-center gap-4 max-w-full">
        <select v-model="filters.project_id" class="p-3 rounded-lg bg-gray-900/20 border border-gray-700 flex-1 min-w-[200px]">
            <option value="">-- Todos los proyectos --</option>
            <option v-for="project in projects" :key="project.id" :value="project.id">{{ project.name }}</option>
        </select>

        <select v-model="filters.assigned_to" class="p-3 rounded-lg bg-gray-900/20 border border-gray-700 flex-1 min-w-[200px]">
            <option value="">-- Todos los usuarios --</option>
            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
        </select>

        <select v-model="filters.status" class="p-3 rounded-lg bg-gray-900/20 border border-gray-700 flex-1 min-w-[150px]">
            <option value="">-- Todos los estados --</option>
            <option value="pendiente">Pendiente</option>
            <option value="en_progreso">En progreso</option>
            <option value="completada">Completada</option>
        </select>

        <button @click="applyFilter" class="bg-indigo-600 hover:bg-indigo-500 text-white py-3 px-6 rounded-xl">
            Aplicar Filtro
        </button>
    </div>

    <div class="bg-gray-800/60 p-4 rounded-2xl border border-gray-700 overflow-x-auto">
      <table class="w-full table-auto text-left">
        <thead>
          <tr class="text-gray-400">
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Título</th>
            <th class="px-4 py-2">Proyecto</th>
            <th class="px-4 py-2">Usuario asignado</th>
            <th class="px-4 py-2">Estado</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="task in tasks" :key="task.id" class="border-t border-gray-700 hover:bg-gray-700/50">
            <td class="px-4 py-2">{{ task.id }}</td>
            <td class="px-4 py-2">{{ task.title }}</td>
            <td class="px-4 py-2">{{ task.project?.name || 'N/A' }}</td>
            <td class="px-4 py-2">{{ task.assigned_user ? task.assigned_user.name : 'Sin asignar' }}</td>
            <td class="px-4 py-2">{{ task.status }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useProjectStore } from '../stores/project'
import { useUserStore } from '../stores/user'
import { useRouter } from 'vue-router'

const router = useRouter()
const projectStore = useProjectStore()
const userStore = useUserStore()

const tasks = ref([])
const projects = ref([])
const users = ref([])

const filters = ref({
  project_id: '',
  assigned_to: '',
  status: ''
})

const fetchInitialData = async () => {
  await projectStore.fetchAllProjects()
  await userStore.fetchUsers()
  projects.value = projectStore.projects
  users.value = userStore.users
}

const applyFilter = async () => {
  try {
    const query = new URLSearchParams()
    if (filters.value.project_id) query.append('project_id', filters.value.project_id)
    if (filters.value.assigned_to) query.append('assigned_to', filters.value.assigned_to)
    if (filters.value.status) query.append('status', filters.value.status)

    const res = await axios.get(`http://127.0.0.1:8000/api/tasks?${query.toString()}`, {
      headers: { Authorization: `Bearer ${userStore.token}` }
    })

      // 🔹 Log para depuración
    console.log('Tareas filtradas recibidas del backend:', res.data)
    tasks.value = res.data
  } catch (err) {
    console.error('Error al filtrar tareas:', err)
  }
}

onMounted(async () => {
  await fetchInitialData()
  await applyFilter() // Mostrar todas las tareas por defecto
})
</script>
