import {HomePage} from "@/pages/home";
import {RolesType} from "@/shared/const/roles.ts";

export const routes = [
  {
    path: "/",
    component: HomePage,
    meta: {
      requiresAuth: false
    }
  },
  {
    path: "/catalog/product/:id",
    component: () => import("@/pages/product/ui/product-page.vue"),
    meta: {
      requiresAuth: false
    }
  },
  {
    path: "/login",
    component: () => import("@/pages/auth/ui/auth-page.vue"),
    meta: {
      requiresAuth: false
    }
  },
  {
    path: "/orders/checkout",
    component: () => import("@/pages/checkout/ui/checkout-order.vue"),
  },
  {
    path: "/orders",
    component: () => import("@/pages/orders/ui/order-page.vue"),
    meta: {
      requiresAuth: true,
      requiredRole: RolesType.USER
    }
  },
  {
    path: '/admin/dashboard',
    component: () => import("@/pages/admin/ui/admin-dashboard.vue"),
    meta: {
      requiresAuth: true,
      requiredRole: RolesType.ADMIN
    }
  },
  {
    path: "/admin/orders",
    component: () => import("@/pages/admin/ui/admin-orders.vue"),
    meta: {
      requiresAuth: true,
      requiredRole: RolesType.ADMIN
    }
  },
  {
    path: "/forbidden",
    component: () => import("@/pages/forbidden/ui/forbidden-page.vue"),
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/:pathMatch(.*)',
    component: () => import("@/pages/not-found/ui/not-found.vue"),
    meta: {
      requiresAuth: false
    }
  }
]
