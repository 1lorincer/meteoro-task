import {createRouter, createWebHistory} from 'vue-router'
import {routes} from "./router-path.ts";
import {useUserStore} from "@/entities/user/store/user-store.ts";
import {RolesType} from "@/shared/const/roles.ts";

const routerConfig = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
})

routerConfig.beforeEach(async (to, from, next) => {
  const userStore = useUserStore()
  const isAuthenticated = userStore.getters.isLoginUser

  if (to.meta.requiresAuth) {
    if (!isAuthenticated) {
      next({path: '/login', query: {redirect: to.fullPath}})
      return
    }
    if (userStore.user.role === RolesType.DEFAULT) {
      await userStore.actions.getUserData()
    }

    if (to.meta.requiredRole) {
      const userRole = userStore.user.role
      const requiredRole = to.meta.requiredRole

      if (requiredRole === RolesType.ADMIN && !userStore.getters.isAdmin) {
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

export default routerConfig
