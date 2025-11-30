import {HomePage} from "@/pages/home";

export const routes = [
  {
    path: "/",
    component: HomePage
  },
  {
    path: "/catalog",
    component: () => import("@/pages/catalog/ui/catalog-page.vue")
  },
  {
    path: "/catalog/product/:slug",
    component: () => import("@/pages/product/ui/product-page.vue")
  },
  {
    path: "/login",
    component: () => import("@/pages/auth/ui/auth-page.vue")
  },
  {
    path: '/admin/dashboard',
    component: () => import("@/pages/admin/ui/admin-dashboard.vue")
  },
  {
    path: "/admin/orders",
    component: () => import("@/pages/admin/ui/admin-orders.vue")
  }
]
