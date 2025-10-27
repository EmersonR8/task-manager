import { defineStore } from 'pinia'
import axios from 'axios'
import { useRouter } from 'vue-router'  // <-- Importar router

export const useUserStore = defineStore('user', {
  id: 'user', // opcional, ayuda a depurar
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    users: [] // <-- agregamos esto para guardar la lista de usuarios
  }),

  actions: {
    // ------------------------
    // LOGIN
    // ------------------------
    async login(credentials) {
      try {
        const res = await axios.post('http://127.0.0.1:8000/api/login', credentials)
        this.token = res.data.token
        this.user = res.data.user
        localStorage.setItem('token', this.token)
        return true
      } catch (err) {
        console.error(err.response?.data || err)
        return false
      }
    },

    // ------------------------
    // LOGOUT
    // ------------------------
    // stores/user.js
    logout() {
      this.user = null
      this.token = null
      localStorage.removeItem('token')
    }
    ,

    // ------------------------
    // REGISTER
    // ------------------------
    async register(data) {
      try {
        await axios.post('http://127.0.0.1:8000/api/register', {
          name: data.name,
          email: data.email,
          password: data.password,
          role: data.role || 'developer'
        })

        // Auto-login tras registrarse
        const loginOk = await this.login({
          email: data.email,
          password: data.password
        })

        return loginOk
      } catch (err) {
        console.error(err.response?.data || err)
        return false
      }
    },

    // ------------------------
    // FETCH USERS (para asignar tareas)
    // ------------------------
    async fetchUsers() {
      try {
        const res = await axios.get('http://127.0.0.1:8000/api/users', {
          headers: {
            Authorization: `Bearer ${this.token}`
          }
        })
        this.users = res.data
      } catch (err) {
        console.error('Error cargando usuarios:', err.response?.data || err)
      }
    }
  }
})
