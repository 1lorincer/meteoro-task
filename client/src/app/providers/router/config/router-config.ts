import {createRouter, createWebHistory} from 'vue-router'
import {useUserStore} from "@/entities/user"
import {RolesType} from "@/shared/const/roles"
import {routes} from "./router-path"

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
})

router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore()
  const token = localStorage.getItem('token')

  if (token && !userStore.user.id) {
    try {
      await userStore.actions.getUserData()
    } catch (e) {
      localStorage.removeItem('token')
    }
  }

  const isAuthenticated = !!token && !!userStore.user.id

  if (to.meta.requiresAuth) {
    if (!isAuthenticated) {
      next({
        path: '/login',
        query: {redirect: to.fullPath}
      })
      return
    }

    if (to.meta.requiredRole) {
      const userRole = userStore.user.role
      const requiredRole = to.meta.requiredRole as RolesType

      if (requiredRole === RolesType.ADMIN && !userStore.isAdmin) {
        next('/forbidden')
        return
      }

      if (requiredRole === RolesType.USER && userRole === RolesType.DEFAULT) {
        next('/forbidden')
        return
      }
    }
  }

  if (to.path === '/login' && isAuthenticated) {
    next('/')
    return
  }

  next()
})

export default router
