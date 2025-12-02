<script setup lang="ts">
import {ref} from "vue";
import {useRouter} from "vue-router";
import Menubar from "primevue/menubar";
import Button from "primevue/button";
import Badge from "primevue/badge";
import {useToggleSidebar} from "@/features/toggle-sidebar";
import {useCartStore} from "@/features/cart/store/cart-store";
import {useUserStore} from "@/entities/user";
import {BurgerMenu} from "@/shared/ui/burger";

const router = useRouter()
const items = ref([])
const {getters: userGetters, actions: userActions} = useUserStore()
const {actions: toggleSidebarActions} = useToggleSidebar()
const {getters: cartStoreGetters} = useCartStore()

const openCart = () => {
  toggleSidebarActions.open('cart')
}

const openMenu = () => {
  toggleSidebarActions.open('menu')
}
const handleLogout = () => {
  userActions.logout()
  router.push('/login')
}
</script>

<template>
  <Menubar :model="items" class="py-5 px-6">
    <template #start>
      <router-link :to="userGetters.isAdmin ? '/admin/dashboard' : '/'"
                   class="text-2xl font-semibold">
        Meteoro {{ userGetters.isAdmin ? 'Dashboard' : 'Market' }}
      </router-link>
    </template>
    <template #end>
      <div v-if="userGetters.isLoginUser && userGetters.isUser" class="flex items-center gap-3">
        <div class="relative">
          <Button
            icon="pi pi-shopping-cart"
            severity="secondary"
            text
            rounded
            @click="openCart"
          />
          <Badge
            v-if="cartStoreGetters.totalItems > 0"
            :value="cartStoreGetters.totalItems"
            severity="danger"
            class="absolute -top-1 -right-1 !min-w-5 !h-5 !text-xs"
          />
        </div>

        <BurgerMenu @click="openMenu"/>
      </div>
      <Button v-else-if="userGetters.isAdmin" severity="danger" @click="handleLogout">
        Выйти
      </Button>
      <Button v-else severity="success" label="Войти" @click="router.push('/login')"/>
    </template>
  </Menubar>
</template>

<style scoped>

</style>
