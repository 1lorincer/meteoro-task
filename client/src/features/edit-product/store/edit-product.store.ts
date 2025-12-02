import {defineStore} from "pinia"
import {ref} from "vue"
import {adminProductsApi} from "@/entities/admin/products"
import type {UpdateAdminProductDto} from "@/entities/admin/products"
import {useToast} from "primevue"

export const useEditProductStore = defineStore('editProduct', () => {
  const loading = ref(false)
  const toast = useToast()

  const updateProduct = async (id: number, data: UpdateAdminProductDto) => {
    try {
      loading.value = true
      await adminProductsApi.updateProduct(id, data)
      toast.add({
        severity: 'success',
        summary: 'Успешно',
        detail: 'Товар успешно обновлен',
        life: 3000,
      })
      return true
    } catch (error) {
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: 'Не удалось обновить товар',
        life: 3000,
      })
      return false
    } finally {
      loading.value = false
    }
  }

  return {
    loading,
    updateProduct
  }
})
