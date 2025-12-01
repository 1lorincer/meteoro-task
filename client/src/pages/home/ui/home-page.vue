<script setup lang="ts">
import {onMounted, ref} from "vue";
import {productApi} from "@/entities/product/api/product-api.ts";
import type {PaginatedResponse} from "@/shared/api/types.ts";
import type {Product} from "@/entities/product";
import {ProductGrid} from "@/widgets/product-grid";

const isLoading = ref(false)
const products = ref<PaginatedResponse<Product>>({
  data: [],
  links: {first: "", last: "", next: null, prev: null},
  meta: {current_page: 0, last_page: 0, per_page: 0, total: 0}
})
onMounted(async () => {
  isLoading.value = true
  products.value = (await productApi.getAll())
  isLoading.value = false
})
</script>

<template>
  <div>
    <h1 class="text-4xl font-semibold">
      Каталог
    </h1>
    <ProductGrid
        class="mt-10"
        :products="products.data"
        :loading="isLoading"
    />
  </div>
</template>

<style scoped>

</style>
