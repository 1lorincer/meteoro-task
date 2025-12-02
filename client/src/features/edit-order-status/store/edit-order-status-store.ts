import {defineStore} from 'pinia'
import {ref} from 'vue'
import {useToast} from 'primevue'
import {adminOrdersApi} from '@/entities/admin/orders'
import type {StatusType} from '@/entities/order'

export const useEditOrderStatusStore = defineStore('editOrderStatus', () => {
  const loading = ref(false)
  const toast = useToast()

  const updateOrderStatus = async (orderId: number, status: StatusType): Promise<boolean> => {
    try {
      loading.value = true
      await adminOrdersApi.updateOrderStatus(orderId, status)
      toast.add({
        severity: 'success',
        summary: 'Успешно',
        detail: 'Статус заказа успешно обновлён',
        life: 3000,
      })
      return true
    } catch (error: any) {
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: error.response?.data?.message || 'Не удалось обновить статус заказа',
        life: 3000,
      })
      return false
    } finally {
      loading.value = false
    }
  }

  return {
    loading,
    updateOrderStatus,
  }
})