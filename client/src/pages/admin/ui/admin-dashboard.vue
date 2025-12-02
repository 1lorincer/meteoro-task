<script setup lang="ts">
import {computed, onMounted, ref} from "vue";
import {useToast} from "primevue";
import Button from "primevue/button";
import {ProductTable} from "@/widgets/product-table";
import {AdminNavigation} from "@/widgets/admin-navigation";
import {adminProductsApi} from "@/entities/admin/products";
import type {Product} from "@/entities/product";
import {EditProductDialog, useEditProductStore} from "@/features/edit-product";
import {DeleteConfirmDialog, useDeleteProductStore} from "@/features/delete-product";
import {CreateProductDialog, useCreateProductStore} from "@/features/create-product";
import type {CategoryModel} from "@/entities/category";

const products = ref<Product[]>([])
const loading = ref(true)
const toast = useToast();

const editProductStore = useEditProductStore()
const deleteProductStore = useDeleteProductStore()
const createProductStore = useCreateProductStore()

const showEditDialog = ref(false)
const showDeleteDialog = ref(false)
const showCreateDialog = ref(false)
const selectedProduct = ref<Product | null>(null)

const categories = computed<CategoryModel[]>(() => {
  const uniqueCategories = new Map<number, CategoryModel>()
  products.value.forEach(product => {
    if (product.category && !uniqueCategories.has(product.category.id)) {
      uniqueCategories.set(product.category.id, product.category)
    }
  })
  return Array.from(uniqueCategories.values())
})

const loadProducts = async () => {
  try {
    loading.value = true
    products.value = (await adminProductsApi.getAllProducts()).data
    loading.value = false
  } catch (e) {
    toast.add({
      severity: 'error',
      summary: 'Ошибка',
      detail: 'Не удалось загрузить товары',
      life: 3000,
    })
  }
}

const handleEdit = (product: Product) => {
  selectedProduct.value = product
  showEditDialog.value = true
}

const handleDelete = (product: Product) => {
  selectedProduct.value = product
  showDeleteDialog.value = true
}

const handleSaveEdit = async (data: any) => {
  if (!selectedProduct.value) return

  const success = await editProductStore.updateProduct(selectedProduct.value.id, data)
  if (success) {
    showEditDialog.value = false
    await loadProducts()
  }
}

const handleConfirmDelete = async () => {
  if (!selectedProduct.value) return

  const success = await deleteProductStore.deleteProduct(selectedProduct.value.id)
  if (success) {
    showDeleteDialog.value = false
    await loadProducts()
  }
}

const handleCreateProduct = async (data: any) => {
  const success = await createProductStore.createProduct(data)
  if (success) {
    showCreateDialog.value = false
    await loadProducts()
  }
}

onMounted(() => {
  loadProducts()
})
</script>

<template>
  <div class="flex flex-col gap-4">
    <AdminNavigation/>

    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold">Управление товарами</h1>
      <Button
        label="Добавить товар"
        icon="pi pi-plus"
        severity="success"
        @click="showCreateDialog = true"
      />
    </div>

    <product-table
      :products="products"
      :loading="loading"
      @edit="handleEdit"
      @delete="handleDelete"
    />

    <CreateProductDialog
      v-model:visible="showCreateDialog"
      :categories="categories"
      :loading="createProductStore.loading"
      @save="handleCreateProduct"
    />

    <EditProductDialog
      v-model:visible="showEditDialog"
      :product="selectedProduct"
      :categories="categories"
      :loading="editProductStore.loading"
      @save="handleSaveEdit"
    />

    <DeleteConfirmDialog
      v-model:visible="showDeleteDialog"
      :item-name="selectedProduct?.name || ''"
      :loading="deleteProductStore.loading"
      @confirm="handleConfirmDelete"
    />
  </div>
</template>
<style scoped>

</style>
