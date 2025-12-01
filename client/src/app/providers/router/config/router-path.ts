import {HomePage} from "@/pages/home";

export const routes = [
  {
    path: "/",
    component: HomePage
  },
  {
    path: "/catalog/product/:id",
    component: () => import("@/pages/product/ui/product-page.vue")
  },
  {
    path: "/login",
    component: () => import("@/pages/auth/ui/auth-page.vue")
  },
  {
    path: "/orders",
    component: () => import("@/pages/orders/ui/order-page.vue")
  },
  {
    path: '/admin/dashboard',
    component: () => import("@/pages/admin/ui/admin-dashboard.vue")
  },
  {
    path: "/admin/orders",
    component: () => import("@/pages/admin/ui/admin-orders.vue")
  },
  {
    path: "/forbidden",
    component: () => import("@/pages/forbidden/ui/forbidden-page.vue")
  },
  {
    path: '/:pathMatch(.*)',
    component: () => import("@/pages/not-found/ui/not-found.vue")
  }
]
