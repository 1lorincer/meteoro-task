import {defineStore} from "pinia"
import {ref} from "vue"
import {adminProductsApi} from "@/entities/admin/products"
import {useToast} from "primevue"

export const useDeleteProductStore = defineStore('deleteProduct', () => {
  const loading = ref(false)
  const toast = useToast()

  const deleteProduct = async (id: number) => {
    try {
      loading.value = true
      await adminProductsApi.deleteProduct(id)
      toast.add({
        severity: 'success',
        summary: 'Успешно',
        detail: 'Товар успешно удален',
        life: 3000,
      })
      return true
    } catch (error) {
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: 'Не удалось удалить товар',
        life: 3000,
      })
      return false
    } finally {
      loading.value = false
    }
  }

  return {
    loading,
    deleteProduct
  }
})
