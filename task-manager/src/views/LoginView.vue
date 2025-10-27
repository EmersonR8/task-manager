<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-gray-900 text-gray-100 px-4">
    <div class="bg-gray-800/60 backdrop-blur-md shadow-lg rounded-2xl p-8 w-full max-w-sm border border-gray-700">
      <h2 class="text-3xl font-extrabold mb-6 text-center">Iniciar sesión</h2>

      <input
        v-model="email"
        type="email"
        placeholder="Email"
        class="w-full bg-gray-900/20 text-gray-100 border border-gray-700 p-3 mb-4 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
      />
      <input
        v-model="password"
        type="password"
        placeholder="Contraseña"
        class="w-full bg-gray-900/20 text-gray-100 border border-gray-700 p-3 mb-4 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
      />

      <button
        @click="handleLogin"
        class="w-full bg-indigo-600 hover:bg-indigo-500 transition-colors text-white py-3 rounded-xl font-medium shadow-md hover:shadow-lg"
      >
        Ingresar
      </button>

      <p class="text-gray-400 text-sm mt-4 text-center">
        ¿No tienes cuenta? <router-link to="/registerUser" class="text-indigo-400 hover:underline">Regístrate</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '../stores/user'
import Swal from 'sweetalert2'  // <-- Importamos SweetAlert2

const email = ref('')
const password = ref('')
const router = useRouter()
const userStore = useUserStore()

const handleLogin = async () => {
  const ok = await userStore.login({
    email: email.value,
    password: password.value
  })
  if (ok) {
    // Redirigir según rol
    if (userStore.user.role === 'admin') {
      router.push('/dashboard/admin')
    } else {
      router.push('/dashboard/dev')
    }
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Credenciales inválidas',
      confirmButtonColor: '#6366f1' 
    })
  }
}
</script>
