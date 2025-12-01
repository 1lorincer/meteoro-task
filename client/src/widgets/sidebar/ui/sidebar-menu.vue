<script setup lang="ts">
import {storeToRefs} from "pinia";
import {useRouter} from "vue-router";
import Button from "primevue/button";
import Avatar from "primevue/avatar";
import {useToggleSidebar} from "@/features/toggle-sidebar";
import {useUserStore} from "@/entities/user";
import {useCartStore} from "@/features/cart/store/cart-store";
import CartItem from "@/features/cart/ui/cart-item.vue";
import {RolesType} from "@/shared/const/roles";
import {formatPrice} from "@/shared/lib/helpers/formatPrice.ts";

const sidebarStore = useToggleSidebar();
const {isOpen, mode} = storeToRefs(sidebarStore);
const {user} = storeToRefs(useUserStore());
const cartStore = useCartStore();
const {items} = storeToRefs(cartStore);
const router = useRouter();

const menuItems = [
  {label: 'Главная', icon: 'pi pi-home', route: '/'},
  {label: 'Мои заказы', icon: 'pi pi-shopping-cart', route: '/orders'},
];

const navigateTo = (route: string) => {
  router.push(route);
  sidebarStore.actions.close();
};

const handleLogout = () => {
  localStorage.removeItem('token');
  user.value = {
    id: null,
    name: '',
    email: '',
    role: RolesType.DEFAULT
  };
  router.push('/auth?status=login');
  sidebarStore.actions.close();
};

const closeSidebar = () => {
  sidebarStore.actions.close();
};

const handleCheckout = () => {
  router.push('/checkout');
  sidebarStore.actions.close();
};
</script>

<template>
  <Transition name="fade">
    <div
      v-if="isOpen"
      class="fixed inset-0 bg-black/50 z-40"
      @click="closeSidebar"
    />
  </Transition>

  <aside
    class="fixed top-0 right-0 h-full w-1/3 bg-zinc-900 shadow-2xl z-50 flex flex-col transform transition-transform duration-300 ease-in-out"
    :class="isOpen ? 'translate-x-0' : 'translate-x-full'"
  >
    <template v-if="mode === 'menu'">
      <!-- Header -->
      <div class="p-4 border-b flex items-center justify-between">
        <h2 class="text-xl font-bold">Меню</h2>
        <Button
          icon="pi pi-times"
          text
          rounded
          severity="secondary"
          @click="closeSidebar"
        />
      </div>

      <!-- User Info -->
      <div v-if="user?.id" class="p-4 border-b">
        <div class="flex items-center gap-3">
          <Avatar
            :label="user.name?.[0]?.toUpperCase()"
            size="large"
            shape="circle"
            class="bg-primary text-white"
          />
          <div class="flex-1 min-w-0">
            <p class="font-semibold truncate">{{ user.name }}</p>
            <p class="text-sm text-gray-500 truncate">{{ user.email }}</p>
          </div>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto p-4">
        <div class="flex flex-col gap-1">
          <Button
            v-for="item in menuItems"
            :key="item.route"
            :label="item.label"
            :icon="item.icon"
            severity="secondary"
            text
            class="justify-start"
            @click="navigateTo(item.route)"
          />
        </div>
      </nav>

      <div class="p-4 border-t">
        <Button
          label="Выйти"
          icon="pi pi-sign-out"
          severity="danger"
          outlined
          class="w-full"
          @click="handleLogout"
        />
      </div>
    </template>

    <template v-else-if="mode === 'cart'">
      <div class="p-4 border-b flex items-center justify-between">
        <div class="flex items-center gap-2">
          <i class="pi pi-shopping-cart text-xl" />
          <h2 class="text-xl font-bold">Корзина</h2>
          <span v-if="cartStore.getters.totalItems" class="bg-primary text-white rounded-full px-2 py-0.5 text-xs font-bold">
            {{ cartStore.getters.totalItems }}
          </span>
        </div>
        <Button
          icon="pi pi-times"
          text
          rounded
          severity="secondary"
          @click="closeSidebar"
        />
      </div>

      <!-- Cart Items -->
      <div class="flex-1 overflow-y-auto p-4">
        <div v-if="items.length === 0" class="flex flex-col items-center justify-center h-full text-center text-zinc-400">
          <i class="pi pi-shopping-cart text-6xl mb-4" />
          <p class="text-lg">Корзина пуста</p>
          <p class="text-sm mt-2">Добавьте товары для оформления заказа</p>
        </div>

        <div v-else class="flex flex-col gap-3">
          <CartItem
            v-for="item in items"
            :key="item.product.id"
            :item="item"
          />
        </div>
      </div>

      <!-- Cart Footer -->
      <div v-if="items.length > 0" class="p-4 border-t space-y-3">
        <div class="flex justify-between items-center text-lg font-bold">
          <span>Итого:</span>
          <span>{{ formatPrice(cartStore.getters.totalPrice) }} </span>
        </div>

        <Button
          label="Оформить заказ"
          icon="pi pi-check"
          class="w-full"
          severity="success"
          @click="handleCheckout"
        />

        <Button
          label="Очистить корзину"
          icon="pi pi-trash"
          outlined
          severity="danger"
          class="w-full"
          @click="cartStore.actions.clear"
        />
      </div>
    </template>
  </aside>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
