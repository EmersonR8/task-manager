<template>
  <div class="p-6 min-h-screen bg-gray-900 text-gray-100">
     <!-- Botón Volver -->
    <button
      @click="goBack"
      class="mb-4 bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-xl"
    >
      ← Volver
    </button>

    <h1 class="text-3xl font-bold mb-6">Editar Tarea</h1>

    <div class="bg-gray-800/60 p-4 rounded-2xl border border-gray-700">
      <input
        v-model="task.title"
        type="text"
        placeholder="Título"
        class="w-full mb-2 p-2 rounded-md bg-gray-900/20 border border-gray-700 placeholder-gray-400"
      />
      <textarea
        v-model="task.description"
        placeholder="Descripción"
        class="w-full mb-2 p-2 rounded-md bg-gray-900/20 border border-gray-700 placeholder-gray-400"
      ></textarea>
      <select
        v-model="task.status"
        class="w-full mb-2 p-2 rounded-md bg-gray-900/20 border border-gray-700"
      >
        <option value="pendiente">Pendiente</option>
        <option value="en_progreso">En progreso</option>
        <option value="completada">Completada</option>
      </select>
      <select
        v-model="task.assigned_to"
        class="w-full mb-4 p-2 rounded-md bg-gray-900/20 border border-gray-700"
      >
        <option value="">-- Asignar a usuario --</option>
        <option v-for="user in users" :key="user.id" :value="user.id">
          {{ user.name }} ({{ user.role }})
        </option>
      </select>
      <div class="flex gap-2">
        <button
          @click="updateTask"
          class="bg-green-600 hover:bg-green-500 text-white py-2 px-4 rounded-xl font-medium"
        >
          Guardar
        </button>
        <button
          @click="cancel"
          class="bg-gray-600 hover:bg-gray-500 text-white py-2 px-4 rounded-xl font-medium"
        >
          Cancelar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useProjectStore } from '../stores/project'
import { useUserStore } from '../stores/user'
import Swal from 'sweetalert2'  // <-- Importamos SweetAlert2

const route = useRoute()
const router = useRouter()
const projectStore = useProjectStore()
const userStore = useUserStore()

const taskId = route.params.id

const task = reactive({
  title: '',
  description: '',
  status: 'pendiente',
  assigned_to: '',
  project_id: null
})

const users = ref([])

// Cargar información de la tarea y mapear campos al formulario
const loadTask = async () => {
  const res = await projectStore.fetchTask(taskId)
  if (!res) return

  task.title = res.title || ''
  task.description = res.description || ''
  task.status = res.status || 'pendiente'
  task.assigned_to = res.assigned_to || ''
  task.project_id = res.project_id
}

// Cargar usuarios para el select
const loadUsers = async () => {
  await userStore.fetchUsers()
  users.value = userStore.users
}

// Guardar cambios
const updateTask = async () => {
  const ok = await projectStore.updateTask(taskId, {
    title: task.title,
    description: task.description,
    status: task.status,
    assigned_to: task.assigned_to
  })
  if (ok) {
    // Redirige al proyecto donde estaba la tarea
    router.push({ name: 'projectView', params: { id: task.project_id } })
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Error al actualizar la tarea',
      confirmButtonColor: '#6366f1'
    })
  }
}

const cancel = () => {
  router.back()
}

const goBack = () => {
  router.back()
}

onMounted(async () => {
  await loadUsers()
  await loadTask()
})
</script>
