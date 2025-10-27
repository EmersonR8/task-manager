import { defineStore } from 'pinia'
import axios from 'axios'
import { useUserStore } from './user'

export const useProjectStore = defineStore('project', {
  state: () => ({
    projects: [],        // Todos los proyectos (admin)
    userProjects: [],    // Proyectos del developer
    projectDetails: {},  // Detalles de un proyecto con tareas
    developers: [],      // Lista de developers (admin)
  }),

  actions: {
    // --------------------------
    // Traer proyectos del developer
    // --------------------------
    async fetchUserProjects() {
  const userStore = useUserStore()
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/projects', {
      headers: { Authorization: `Bearer ${userStore.token}` }
    })
    this.userProjects = res.data
  } catch (err) {
    console.error('Error fetching user projects:', err)
  }
},

    // --------------------------
    // Traer todos los proyectos (admin)
    // --------------------------
    async fetchAllProjects() {
      const userStore = useUserStore()
      try {
        const res = await axios.get('http://127.0.0.1:8000/api/projects', {
          headers: { Authorization: `Bearer ${userStore.token}` }
        })
        this.projects = res.data
      } catch (err) {
        console.error('Error fetching all projects:', err)
      }
    },

    // --------------------------
    // Traer todos los developers (admin)
    // --------------------------
    async fetchDevelopers() {
      const userStore = useUserStore()
      try {
        const res = await axios.get('http://127.0.0.1:8000/api/users?role=developer', {
          headers: { Authorization: `Bearer ${userStore.token}` }
        })
        this.developers = res.data
      } catch (err) {
        console.error('Error fetching developers:', err)
      }
    },

    // --------------------------
    // Traer un proyecto con sus tareas
    // --------------------------
    async fetchProjectDetails(projectId) {
      const userStore = useUserStore()
      try {
        const res = await axios.get(`http://127.0.0.1:8000/api/projects/${projectId}`, {
          headers: { Authorization: `Bearer ${userStore.token}` }
        })
        this.projectDetails = res.data
      } catch (err) {
        console.error('Error fetching project details:', err)
      }
    },

    // --------------------------
    // Crear proyecto
    // --------------------------
    async createProject(payload) {
      const userStore = useUserStore()
      try {
        await axios.post('http://127.0.0.1:8000/api/projects', payload, {
          headers: { Authorization: `Bearer ${userStore.token}` }
        })
        return true
      } catch (err) {
        console.error('Error creating project:', err)
        return false
      }
    },

    // --------------------------
    // Actualizar proyecto
    // --------------------------
    async updateProject(projectId, payload) {
      const userStore = useUserStore()
      try {
        await axios.put(`http://127.0.0.1:8000/api/projects/${projectId}`, payload, {
          headers: { Authorization: `Bearer ${userStore.token}` }
        })
        return true
      } catch (err) {
        console.error('Error updating project:', err)
        return false
      }
    },

    // --------------------------
    // Crear tarea
    // --------------------------
    async createTask(payload) {
      const userStore = useUserStore()
      try {
        await axios.post('http://127.0.0.1:8000/api/tasks', payload, {
          headers: { Authorization: `Bearer ${userStore.token}` }
        })
        return true
      } catch (err) {
        console.error('Error creating task:', err)
        return false
      }
    },

    // --------------------------
    // Actualizar tarea
    // --------------------------
    async updateTask(taskId, payload) {
      const userStore = useUserStore()
      try {
        await axios.put(`http://127.0.0.1:8000/api/tasks/${taskId}`, payload, {
          headers: { Authorization: `Bearer ${userStore.token}` }
        })
        return true
      } catch (err) {
        console.error('Error updating task:', err)
        return false
      }
    },

    // --------------------------
    // Eliminar tarea
    // --------------------------
    async deleteTask(taskId) {
      const userStore = useUserStore()
      try {
        await axios.delete(`http://127.0.0.1:8000/api/tasks/${taskId}`, {
          headers: { Authorization: `Bearer ${userStore.token}` }
        })
        return true
      } catch (err) {
        console.error('Error deleting task:', err)
        return false
      }
    },

    async fetchTask(taskId) {
        const userStore = useUserStore()
        try {
            const res = await axios.get(`http://127.0.0.1:8000/api/tasks/${taskId}`, {
            headers: { Authorization: `Bearer ${userStore.token}` }
            })
            return res.data
        } catch (err) {
            console.error('Error fetching task:', err.response?.data || err)
            return null
        }
        }
    
  }
})
