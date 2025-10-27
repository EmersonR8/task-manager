<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-gray-900 text-gray-100 px-4">
    <div class="bg-gray-800/60 backdrop-blur-md shadow-lg rounded-2xl p-8 w-full max-w-sm border border-gray-700">
      <h2 class="text-3xl font-extrabold mb-6 text-center">Crear cuenta</h2>

      <input
        v-model="name"
        type="text"
        placeholder="Nombre completo"
        class="w-full bg-gray-900/20 text-gray-100 border border-gray-700 p-3 mb-4 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
      />
      <input
        v-model="email"
        type="email"
        placeholder="Correo electrónico"
        class="w-full bg-gray-900/20 text-gray-100 border border-gray-700 p-3 mb-4 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
      />
      <input
        v-model="password"
        type="password"
        placeholder="Contraseña"
        class="w-full bg-gray-900/20 text-gray-100 border border-gray-700 p-3 mb-4 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
      />
      <input
        v-model="confirmPassword"
        type="password"
        placeholder="Confirmar contraseña"
        class="w-full bg-gray-900/20 text-gray-100 border border-gray-700 p-3 mb-4 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
      />

      <!-- SELECT para el rol -->
      <select
        v-model="role"
        class="w-full bg-gray-900/20 text-gray-100 border border-gray-700 p-3 mb-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
      >
        <option value="developer">Developer</option>
        <option value="admin">Admin</option>
      </select>

      <button
        @click="handleRegister"
        class="w-full bg-indigo-600 hover:bg-indigo-500 transition-colors text-white py-3 rounded-xl font-medium shadow-md hover:shadow-lg"
      >
        Registrarse
      </button>

      <p class="text-gray-400 text-sm mt-4 text-center">
        ¿Ya tienes cuenta? <router-link to="/login" class="text-indigo-400 hover:underline">Inicia sesión</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '../stores/user'
import Swal from 'sweetalert2'

const name = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')
const role = ref('developer') // valor por defecto

const router = useRouter()
const userStore = useUserStore()

const handleRegister = async () => {
  if (password.value !== confirmPassword.value) {
    await Swal.fire({
      icon: 'error',
      title: 'Error',
      text: '⚠️ Las contraseñas no coinciden'
    })
    return
  }

  const ok = await userStore.register({
    name: name.value,
    email: email.value,
    password: password.value,
    role: role.value
  })

  if (ok) {
    await Swal.fire({
      icon: 'success',
      title: 'Cuenta creada',
      text: '✅ Tu cuenta ha sido creada correctamente',
      timer: 1500,
      showConfirmButton: false
    })

    // Login segun rol
    const loginOk = await userStore.login({
      email: email.value,
      password: password.value
    })

    if (loginOk) {
      // Redirigir según el rol
      if (userStore.user.role === 'admin') router.push('/dashboard/admin')
      else if (userStore.user.role === 'developer') router.push('/dashboard/dev')
    } else {
      await Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '⚠️ Hubo un problema al iniciar sesión automáticamente'
      })
    }

  } else {
    await Swal.fire({
      icon: 'error',
      title: 'Error',
      text: '⚠️ Error al crear la cuenta'
    })
  }
}
</script>
