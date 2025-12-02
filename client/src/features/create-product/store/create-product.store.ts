import {defineStore} from "pinia"
import {ref} from "vue"
import {adminProductsApi} from "@/entities/admin/products"
import type {AdminProductDto} from "@/entities/admin/products"
import {useToast} from "primevue"

export const useCreateProductStore = defineStore('createProduct', () => {
  const loading = ref(false)
  const toast = useToast()

  const createProduct = async (data: AdminProductDto) => {
    try {
      loading.value = true
      await adminProductsApi.createProduct(data)
      toast.add({
        severity: 'success',
        summary: 'Успешно',
        detail: 'Товар успешно создан',
        life: 3000,
      })
      return true
    } catch (error) {
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: 'Не удалось создать товар',
        life: 3000,
      })
      return false
    } finally {
      loading.value = false
    }
  }

  return {
    loading,
    createProduct
  }
})