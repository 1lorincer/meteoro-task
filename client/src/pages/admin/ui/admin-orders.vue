<script setup lang="ts">
import {computed, onMounted, ref} from "vue"
import {useToast} from "primevue"
import Button from "primevue/button"
import {OrderTable} from "@/widgets/order-table"
import {AdminNavigation} from "@/widgets/admin-navigation"
import {adminOrdersApi} from "@/entities/admin/orders"
import type {OrderModel, StatusType} from "@/entities/order"
import {EditOrderStatusDialog, useEditOrderStatusStore} from "@/features/edit-order-status"
import {ViewOrderDetailsDialog} from "@/features/view-order-details"

const orders = ref<OrderModel[]>([])
const loading = ref(true)
const toast = useToast()

const editOrderStatusStore = useEditOrderStatusStore()

const showEditStatusDialog = ref(false)
const showDetailsDialog = ref(false)
const selectedOrder = ref<OrderModel | null>(null)

const orderStats = computed(() => {
  const total = orders.value.length
  const pending = orders.value.filter(o => o.status === 'pending').length
  const processing = orders.value.filter(o => o.status === 'processing').length
  const completed = orders.value.filter(o => o.status === 'completed').length
  const cancelled = orders.value.filter(o => o.status === 'cancelled').length

  return {total, pending, processing, completed, cancelled}
})

const loadOrders = async () => {
  try {
    loading.value = true
    const response = await adminOrdersApi.getAllOrders()
    orders.value = response.data
  } catch (e) {
    toast.add({
      severity: 'error',
      summary: 'Ошибка',
      detail: 'Не удалось загрузить заказы',
      life: 3000,
    })
  } finally {
    loading.value = false
  }
}

const handleEditStatus = (order: OrderModel) => {
  selectedOrder.value = order
  showEditStatusDialog.value = true
}

const handleViewDetails = (order: OrderModel) => {
  selectedOrder.value = order
  showDetailsDialog.value = true
}

const handleSaveStatus = async (orderId: number, status: StatusType) => {
  const success = await editOrderStatusStore.updateOrderStatus(orderId, status)
  if (success) {
    showEditStatusDialog.value = false
    await loadOrders()
  }
}

onMounted(() => {
  loadOrders()
})
</script>

<template>
  <div class="flex flex-col gap-4">
    <AdminNavigation/>

    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold">Управление заказами</h1>
      <Button
        label="Обновить"
        icon="pi pi-refresh"
        severity="info"
        @click="loadOrders"
        :loading="loading"
      />
    </div>
    <div class="grid grid-cols-5 gap-4">
      <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="text-sm text-blue-600 font-medium">Всего заказов</div>
        <div class="text-2xl font-bold text-blue-700 mt-1">{{ orderStats.total }}</div>
      </div>
      <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
        <div class="text-sm text-yellow-600 font-medium">Ожидает</div>
        <div class="text-2xl font-bold text-yellow-700 mt-1">{{ orderStats.pending }}</div>
      </div>
      <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
        <div class="text-sm text-indigo-600 font-medium">В обработке</div>
        <div class="text-2xl font-bold text-indigo-700 mt-1">{{ orderStats.processing }}</div>
      </div>
      <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
        <div class="text-sm text-green-600 font-medium">Завершено</div>
        <div class="text-2xl font-bold text-green-700 mt-1">{{ orderStats.completed }}</div>
      </div>
      <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="text-sm text-red-600 font-medium">Отменено</div>
        <div class="text-2xl font-bold text-red-700 mt-1">{{ orderStats.cancelled }}</div>
      </div>
    </div>

    <OrderTable
      :orders="orders"
      :loading="loading"
      @edit-status="handleEditStatus"
      @view-details="handleViewDetails"
    />

    <EditOrderStatusDialog
      v-model:visible="showEditStatusDialog"
      :order="selectedOrder"
      :loading="editOrderStatusStore.loading"
      @save="handleSaveStatus"
    />

    <ViewOrderDetailsDialog
      v-model:visible="showDetailsDialog"
      :order="selectedOrder"
    />
  </div>
</template>

<style scoped>
</style>
