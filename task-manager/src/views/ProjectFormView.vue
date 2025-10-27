<template>
  <div class="relative p-6 min-h-screen bg-gray-900 text-gray-100 flex justify-center items-start">
    
    <!-- Botón Volver -->
    <button
      @click="goBack"
      class="absolute top-6 left-6 bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-xl"
    >
      ← Volver
    </button>

    <div class="bg-gray-800/60 p-6 rounded-2xl shadow-md w-full max-w-md border border-gray-700 mt-12">
      <h2 class="text-2xl font-bold mb-4">{{ editing ? 'Editar Proyecto' : 'Crear Proyecto' }}</h2>

      <input v-model="name" type="text" placeholder="Nombre del proyecto"
             class="w-full mb-3 p-3 rounded-lg bg-gray-900/20 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"/>

      <textarea v-model="description" placeholder="Descripción"
                class="w-full mb-3 p-3 rounded-lg bg-gray-900/20 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>

      <select v-model="assignedUser" class="w-full mb-4 p-3 rounded-lg bg-gray-900/20 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option disabled value="">Selecciona usuario</option>
        <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
      </select>

      <button @click="handleSubmit" class="w-full bg-indigo-600 hover:bg-indigo-500 py-3 rounded-xl font-medium shadow-md hover:shadow-lg">
        {{ editing ? 'Actualizar' : 'Crear' }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useUserStore } from '../stores/user'
import { useRoute, useRouter } from 'vue-router'
import Swal from 'sweetalert2'

const userStore = useUserStore()
const route = useRoute()
const router = useRouter()

const name = ref('')
const description = ref('')
const assignedUser = ref('')
const users = ref([])
const editing = ref(false)
const projectId = ref(null)

onMounted(async () => {
  try {
    // Cargar lista de usuarios
    const res = await axios.get('http://127.0.0.1:8000/api/users?role=developer', {
      headers: { Authorization: `Bearer ${userStore.token}` }
    })
    users.value = res.data

    // Si hay un ID en la ruta, cargar datos del proyecto
    if (route.params.id) {
      editing.value = true
      projectId.value = route.params.id
      await loadProjectData(projectId.value)
    }

  } catch (err) {
    console.error(err)
  }
})

// Cargar datos del proyecto existente
const loadProjectData = async (id) => {
  try {
    const res = await axios.get(`http://127.0.0.1:8000/api/projects/${id}`, {
      headers: { Authorization: `Bearer ${userStore.token}` }
    })
    const project = res.data
    name.value = project.name
    description.value = project.description
    assignedUser.value = project.user_id || ''
  } catch (err) {
    console.error('Error cargando proyecto:', err)
  }
}

const handleSubmit = async () => {
  try {
    if (editing.value && projectId.value) {
      // Actualizar proyecto existente
      const res = await axios.put(
        `http://127.0.0.1:8000/api/projects/${projectId.value}`,
        {
          name: name.value,
          description: description.value,
          user_id: assignedUser.value
        },
        { headers: { Authorization: `Bearer ${userStore.token}` } }
      )
      if (res.status >= 200 && res.status < 300) {
        await Swal.fire({
          icon: 'success',
          title: 'Proyecto actualizado',
          text: '✅ Proyecto actualizado correctamente',
          timer: 2000,
          showConfirmButton: false
        })
        router.back() // vuelve a la vista anterior
      }
    } else {
      // Crear nuevo proyecto
      const res = await axios.post(
        'http://127.0.0.1:8000/api/projects',
        {
          name: name.value,
          description: description.value,
          user_id: assignedUser.value
        },
        { headers: { Authorization: `Bearer ${userStore.token}` } }
      )
      if (res.status >= 200 && res.status < 300) {
        await Swal.fire({
          icon: 'success',
          title: 'Proyecto creado',
          text: '✅ Proyecto creado correctamente',
          timer: 2000,
          showConfirmButton: false
        })
        router.back() // vuelve a la vista anterior
      }
    }
  } catch (err) {
    console.error('Error al guardar proyecto:', err.response || err)
    await Swal.fire({
      icon: 'error',
      title: 'Error',
      text: '⚠️ Error al guardar proyecto. Revisa los datos.'
    })
  }
}

const goBack = () => {
  router.back()
}
</script>
