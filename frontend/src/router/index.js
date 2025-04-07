import { createRouter, createWebHistory } from 'vue-router'
import InvoicesView from '@/views/InvoicesView.vue'
import CreateView from '@/views/CreateView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: InvoicesView,
    },
    {
      path: '/invoices',
      name: 'invoices',
      component: InvoicesView,
    },
    {
      path: '/create/:type',
      name: 'create',
      component: CreateView,
    },
  ],
})

export default router
