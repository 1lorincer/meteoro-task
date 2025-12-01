<script setup lang="ts">
import {ref, watchEffect} from "vue";
import {useRouter} from "vue-router";
import Menubar from "primevue/menubar";
import Button from "primevue/button";
import Badge from "primevue/badge";
import {useToggleSidebar} from "@/features/toggle-sidebar";
import {useUserStore} from "@/entities/user";
import {useCartStore} from "@/features/cart/store/cart-store";
import {BurgerMenu} from "@/shared/ui/burger";

const router = useRouter()
const items = ref([])
const {getters: userGetters} = useUserStore()
const {actions: toggleSidebarActions} = useToggleSidebar()
const {getters: cartStoreGetters} = useCartStore()

const openCart = () => {
  toggleSidebarActions.open('cart')
}

const openMenu = () => {
  toggleSidebarActions.open('menu')
}
</script>

<template>
  <Menubar :model="items" class="py-5 px-6">
    <template #start>
      <router-link to="/" class="text-2xl font-semibold">
        Meteoro market
      </router-link>
    </template>
    <template #end>
      <div v-if="userGetters.isLoginUser" class="flex items-center gap-3">
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
      <Button v-else severity="success" label="Войти" @click="router.push('/login')"/>
    </template>
  </Menubar>
</template>

<style scoped>

</style>
