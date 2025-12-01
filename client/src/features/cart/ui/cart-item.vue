<script setup lang="ts">
import Button from "primevue/button";
import InputNumber from "primevue/inputnumber";
import type {CartItem} from "@/features/cart/models/cart-model.ts";
import {useCartStore} from "@/features/cart/store/cart-store.ts";
import {formatPrice} from "@/shared/lib/helpers/formatPrice.ts";

interface IProps {
  item: CartItem;
}

const {item} = defineProps<IProps>();
const cartStore = useCartStore();

const updateQuantity = (newQuantity: number | null) => {
  if (newQuantity !== null) {
    cartStore.actions.updateQuantity(item.product.id, newQuantity);
  }
};

const removeFromCart = () => {
  cartStore.actions.updateQuantity(item.product.id, 0);
};
</script>

<template>
  <div class="flex gap-3 p-3 bg-zinc-800 rounded-lg border border-zinc-700">
    <!-- Product Image -->
    <div class="w-20 h-20 flex-shrink-0 bg-zinc-700 rounded overflow-hidden">
      <img
        v-if="item.product.image"
        :src="item.product.image"
        :alt="item.product.name"
        class="w-full h-full object-cover"
      />
      <div v-else class="w-full h-full flex items-center justify-center">
        <i class="pi pi-image text-zinc-500 text-2xl" />
      </div>
    </div>

    <div class="flex-1 min-w-0">
      <h4 class="font-semibold truncate mb-1">{{ item.product.name }}</h4>
      <p class="text-sm text-zinc-400 mb-2">{{ item.product.price }} ₽</p>

      <div class="flex items-center gap-2">
        <InputNumber
          :model-value="item.quantity"
          @update:model-value="updateQuantity"
          :min="1"
          :max="99"
          show-buttons
          button-layout="horizontal"
          :input-style="{width: '3rem'}"
          size="small"
        >
          <template #incrementbuttonicon>
            <span class="pi pi-plus text-xs" />
          </template>
          <template #decrementbuttonicon>
            <span class="pi pi-minus text-xs" />
          </template>
        </InputNumber>

        <Button
          icon="pi pi-trash"
          severity="danger"
          text
          rounded
          size="small"
          @click="removeFromCart"
        />
      </div>
    </div>

    <div class="flex flex-col items-end justify-between">
      <p class="font-bold text-lg">{{ formatPrice(item.product.price * item.quantity) }} </p>
    </div>
  </div>
</template>

<style scoped>
:deep(.p-inputnumber-button) {
  width: 2rem;
  height: 2rem;
}

:deep(.p-inputnumber-input) {
  text-align: center;
  padding: 0.25rem;
}
</style>
