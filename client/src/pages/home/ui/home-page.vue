<script setup lang="ts">
import {onMounted, ref, watch} from "vue";
import {useToast} from "primevue";
import Paginator from 'primevue/paginator';
import {ProductGrid} from "@/widgets/product-grid";
import {CategoryFilter, CategorySearch} from "@/features/catalog-filter";
import {productApi} from "@/entities/product/api/product-api.ts";
import type {Product, ProductListParams} from "@/entities/product";
import {useUserStore} from "@/entities/user";
import {useDebounce} from "@/shared/lib/composable/useDebounce.ts";
import type {PaginatedResponse} from "@/shared/api/types.ts";
import type {SortOrder, SortTypes} from "@/shared/types/sort-types.ts";

const {actions: userActions} = useUserStore()
const toast = useToast();
const isLoading = ref(false)
const products = ref<PaginatedResponse<Product>>({
  current_page: 1,
  data: [],
  first_page_url: "",
  from: 0,
  last_page: 1,
  last_page_url: "",
  links: [],
  next_page_url: null,
  path: "",
  per_page: 12,
  prev_page_url: null,
  to: 0,
  total: 0
})
const {value: search, debouncedValue} = useDebounce("", 500)

const currentPage = ref(1)
const perPage = ref(12)

const sortBy = ref<SortTypes>('created_at')
const sortOrder = ref<SortOrder>('desc')

const sortOptions = [
  {label: 'Цена: По возрастанию', value: 'price-asc'},
  {label: 'Цена: По убыванию', value: 'price-desc'},
  {label: 'Название: А-Я', value: 'name-asc'},
  {label: 'Название: Я-А', value: 'name-desc'},
  {label: 'Дата: Сначала новые', value: 'created_at-desc'},
  {label: 'Дата: Сначала старые', value: 'created_at-asc'}
]

const selectedSort = ref('created_at-desc')

const loadProducts = async (params?: ProductListParams) => {
  try {
    isLoading.value = true
    products.value = (await productApi.getAll(params))
    isLoading.value = false
  } catch (e) {
    toast.add({
      severity: 'error',
      summary: 'Ошибка',
      detail: 'Не удалось загрузить товары',
      life: 3000,
    })
  }
}

const loadProductsWithFilters = () => {
  loadProducts({
    search: search.value,
    page: currentPage.value,
    per_page: perPage.value,
    sort_by: sortBy.value,
    sort_order: sortOrder.value
  })
}

onMounted(() => {
  loadProductsWithFilters()
  userActions.init()
})

watch(debouncedValue, () => {
  currentPage.value = 1
  loadProductsWithFilters()
})

watch(selectedSort, (newValue) => {
  const [field, order] = newValue.split('-')
  sortBy.value = field as 'price' | 'name' | 'created_at'
  sortOrder.value = order as 'asc' | 'desc'
  currentPage.value = 1
  loadProductsWithFilters()
})

const onPageChange = (event: any) => {
  currentPage.value = event.page + 1
  loadProductsWithFilters()
}
</script>

<template>
  <div>
    <h1 class="text-4xl font-semibold">
      Каталог
    </h1>
    <div class="flex justify-between flex-col sm:flex-row items-center gap-5 mt-5">
      <category-filter v-model:selected-sort="selectedSort" :sortOptions="sortOptions"/>
      <category-search v-model:search="search"/>
    </div>
    <ProductGrid
      class="mt-10"
      :products="products.data"
      :loading="isLoading"
    />
    <Paginator
      v-if="products.total > 0"
      class="mt-8"
      :rows="products.per_page"
      :total-records="products.total"
      :first="(products.current_page - 1) * products.per_page"
      @page="onPageChange"
      template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
      current-page-report-template="Показано {first} - {last} из {totalRecords}"
    />
  </div>
</template>

<style scoped>

</style>
