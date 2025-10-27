<template>
  <div class="p-6 min-h-screen bg-gray-900 text-gray-100">

     <!-- Botón Volver -->
    <button
      @click="goBack"
      class="mb-4 bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-xl"
    >
      ← Volver
    </button>

    <h1 class="text-3xl font-bold mb-6">{{ project.name }}</h1>
    <p class="text-gray-400 mb-6">{{ project.description }}</p>

    <!-- Formulario para crear tarea -->
    <div class="bg-gray-800/60 p-4 rounded-2xl mb-6 border border-gray-700">
      <h2 class="text-xl font-semibold mb-4">Crear Nueva Tarea</h2>
      
      <input
        v-model="newTask.title"
        type="text"
        placeholder="Título"
        class="w-full mb-2 p-2 rounded-md bg-gray-900/20 border border-gray-700 placeholder-gray-400"
      />
      
      <textarea
        v-model="newTask.description"
        placeholder="Descripción"
        class="w-full mb-2 p-2 rounded-md bg-gray-900/20 border border-gray-700 placeholder-gray-400"
      ></textarea>

      <select
        v-model="newTask.status"
        class="w-full mb-2 p-2 rounded-md bg-gray-900/20 border border-gray-700"
      >
        <option value="pendiente">Pendiente</option>
        <option value="en_progreso">En progreso</option>
        <option value="completada">Completada</option>
      </select>

      <!-- Asignar a usuario -->
      <select
        v-model="newTask.assigned_to"
        class="w-full mb-4 p-2 rounded-md bg-gray-900/20 border border-gray-700"
      >
        <option value="">-- Asignar a usuario --</option>
        <option v-for="user in users" :key="user.id" :value="user.id">
          {{ user.name }} ({{ user.role }})
        </option>
      </select>

      <button
        @click="addTask"
        class="bg-indigo-600 hover:bg-indigo-500 text-white py-2 px-4 rounded-xl font-medium shadow-md hover:shadow-lg"
      >
        Agregar Tarea
      </button>
    </div>

    <!-- Lista de tareas -->
    <div v-if="project.tasks && project.tasks.length > 0">
      <h2 class="text-2xl font-bold mb-4">Tareas</h2>
      <ul>
        <li
          v-for="task in project.tasks"
          :key="task.id"
          class="bg-gray-800/60 p-4 mb-2 rounded-2xl flex justify-between items-center border border-gray-700"
        >
          <div>
            <span class="font-bold">{{ task.title }}</span> - 
            <span>{{ task.status }}</span>
            <p class="text-gray-400">{{ task.description }}</p>
            <p class="text-sm text-gray-500 italic" v-if="task.assigned_user">
              Asignada a: {{ task.assigned_user.name }}
            </p>
            <p class="text-sm text-gray-500 italic" v-else>
              Sin asignar
            </p>
          </div>
          <div class="flex gap-2">
            <button
              @click="toggleStatus(task)"
              class="bg-yellow-500 hover:bg-yellow-400 text-black px-2 py-1 rounded-md"
            >
              Cambiar Estado
            </button>
            <button
                @click="editTask(task.id)"
                class="bg-blue-600 hover:bg-blue-500 text-white px-2 py-1 rounded-md"
            >
                Editar
            </button>
            <button
              @click="removeTask(task.id)"
              class="bg-red-600 hover:bg-red-500 text-white px-2 py-1 rounded-md"
            >
              Eliminar
            </button>
          </div>
        </li>
      </ul>
    </div>

    <div v-else class="text-gray-400">
      No hay tareas aún para este proyecto.
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import { useRoute } from 'vue-router'
import { useProjectStore } from '../stores/project'
import { useUserStore } from '../stores/user'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'  // <-- Importamos SweetAlert2

const route = useRoute()
const projectStore = useProjectStore()
const userStore = useUserStore()
const router = useRouter()

const projectId = route.params.id

const project = reactive({
  id: null,
  name: '',
  description: '',
  tasks: []
})

const newTask = reactive({
  title: '',
  description: '',
  status: 'pendiente',
  assigned_to: ''
})

const users = ref([])

const loadProject = async () => {
  await projectStore.fetchProjectDetails(projectId)
  Object.assign(project, projectStore.projectDetails)
}

const loadUsers = async () => {
  await userStore.fetchUsers()
  users.value = userStore.users
}

const addTask = async () => {
  if (!newTask.title) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: '⚠️ El titulo es obligatorio.'
    })
    return
  }

  const ok = await projectStore.createTask({ ...newTask, project_id: projectId })
  if (ok) {
    await loadProject()
    Object.assign(newTask, { title: '', description: '', status: 'pendiente', assigned_to: '' })
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: '⚠️ Error al guardar tarea.'
    })
  }
}

const toggleStatus = async (task) => {
  let nextStatus =
    task.status === 'pendiente'
      ? 'en_progreso'
      : task.status === 'en_progreso'
      ? 'completada'
      : 'pendiente'

  const ok = await projectStore.updateTask(task.id, { status: nextStatus })
  if (ok) await loadProject()
}

const removeTask = async (taskId) => {
  const ok = await projectStore.deleteTask(taskId)
  if (ok) await loadProject()
}

const editTask = (taskId) => {
  router.push({ name: 'TaskEdit', params: { id: taskId } })
}

const goBack = () => {
  router.back()
}

onMounted(async () => {
  await loadProject()
  await loadUsers()
})
</script>
