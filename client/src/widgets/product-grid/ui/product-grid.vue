<script setup lang="ts">
import {ref} from "vue";
import DataView from "primevue/dataview";
import Skeleton from "primevue/skeleton";
import {type Product, ProductItem} from "@/entities/product";

interface IProps {
  products: Product[]
  loading: boolean
}

const {products, loading} = defineProps<IProps>()
const layout = ref('grid')

</script>

<template>
  <DataView :value="products" :layout="layout"
            class="!bg-transparent [&_.p-dataview-content]:!bg-transparent"
  >
    <template v-if="loading">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div v-for="i in 8" :key="i" class="flex flex-col gap-3">
          <Skeleton height="200px" class="rounded-lg"/>
          <Skeleton height="1.5rem" width="70%"/>
          <Skeleton height="1rem" width="40%"/>
          <Skeleton height="2rem" width="50%"/>
          <div class="flex gap-2">
            <Skeleton height="2.5rem" class="flex-1"/>
            <Skeleton height="2.5rem" class="flex-1"/>
          </div>
        </div>
      </div>
    </template>
    <template v-else-if="products.length === 0">
      <div class="flex flex-col items-center justify-center py-20 text-center">
        <i class="pi pi-inbox text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold  mb-2">Товары не найдены</h3>
        <p class="text-gray-500">Попробуйте изменить параметры поиска</p>
      </div>
    </template>
    <template #grid="{ items }">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 !gap-6">
        <ProductItem
          v-for="product in items"
          :key="product.id"
          :product="product"
          layout="grid"
        />
      </div>
    </template>
  </DataView>
</template>

<style scoped>

</style>
