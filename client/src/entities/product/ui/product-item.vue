<script setup lang="ts">
import {useRouter} from "vue-router";
import Button from "primevue/button";
import Card from "primevue/card";
import Skeleton from "primevue/skeleton";
import {useToast} from "primevue/usetoast";
import type {Product} from "@/entities/product";
import {useCartStore} from "@/features/cart/store/cart-store";
import {useUserStore} from "@/entities/user";

interface IProps {
  product: Product
}

const {product} = defineProps<IProps>()
const router = useRouter()
const cartStore = useCartStore()
const toast = useToast()
const {getters: userGetters} = useUserStore()
const addToCart = () => {
  if (userGetters.isLoginUser) {
    cartStore.actions.addItem(product, 1)
    toast.add({
      severity: 'success',
      summary: 'Добавлено в корзину',
      detail: `${product.name} добавлен в корзину`,
      life: 3000
    })
  } else {
    router.push('/login')
  }

}

</script>

<template>
  <Card style="width: 100%; overflow: hidden; position: relative;">
    <template #header>
      <img :alt="`product ${product?.name}`" v-if="product.image" :src="product.image || ''"/>
      <Skeleton height="10rem" width="100%" v-else/>
      <div class="absolute right-3 top-3">

      </div>
    </template>
    <template #title>{{ product?.name }}</template>
    <template #subtitle>Категория: {{ product.category?.name }}</template>
    <template #content>
      <p class="m-0 h-16">
        {{ product.description }}
      </p>
    </template>
    <template #footer>
      <div class="flex gap-4 mt-1 flex-col">
        <Button @click="router.push(`/catalog/product/${product.id}`)" label="Просмотреть"
                variant="outlined" class="w-full"/>
        <Button @click="addToCart" label="В корзину" icon="pi pi-shopping-cart" class="w-full"
                severity="secondary"/>
      </div>
    </template>
  </Card>
</template>

<style scoped>

</style>
