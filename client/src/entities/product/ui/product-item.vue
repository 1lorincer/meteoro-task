<script setup lang="ts">
import {useRouter} from "vue-router";
import Button from "primevue/button";
import Card from "primevue/card";
import Skeleton from "primevue/skeleton";
import type {Product} from "@/entities/product";

interface IProps {
  product: Product
}

const {product} = defineProps<IProps>()
const router = useRouter()

</script>

<template>
  <Card style="width: 100%; overflow: hidden">
    <template #header>
      <img :alt="`product ${product?.name}`" v-if="product.image" :src="product.image || ''"/>
      <Skeleton height="10rem" width="100%" v-else/>
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
        <Button @click="router.push(`/catalog/product/${product.id}`)" label="Просмотреть" variant="outlined" class="w-full"/>
        <Button label="Заказать" class="w-full" severity="secondary"/>
      </div>
    </template>
  </Card>
</template>

<style scoped>

</style>
