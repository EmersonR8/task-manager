import { createRouter, createWebHistory } from 'vue-router'

// Importa las vistas existentes
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterUserView from '../views/RegisterUserView.vue'
import DashboardDevView from '../views/DashboardDevView.vue'
import DashboardAdminView from '../views/DashboardAdminView.vue'
import ProjectView from '../views/ProjectView.vue'
import ProjectFormView from '../views/ProjectFormView.vue'
import TaskEditView from '../views/TaskEditView.vue'
import TaskFilterView from '../views/TaskFilterView.vue' 

const routes = [
  { path: '/', name: 'home', component: HomeView },
  { path: '/login', name: 'login', component: LoginView },
  { path: '/registerUser', name: 'registerUser', component: RegisterUserView },

  { path: '/dashboard/dev', name: 'dashboardDev', component: DashboardDevView },
  { path: '/dashboard/admin', name: 'dashboardAdmin', component: DashboardAdminView },
  { path: '/projects/:id', name: 'projectView', component: ProjectView },
  { path: '/projects/form/:id?', name: 'projectForm', component: ProjectFormView }, // id opcional para editar
  { path: '/task/edit/:id', name: 'TaskEdit', component: TaskEditView },
  { path: '/tasks/filter', name: 'taskFilter', component: TaskFilterView }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
