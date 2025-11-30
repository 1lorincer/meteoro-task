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
]
