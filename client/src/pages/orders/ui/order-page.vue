<script setup lang="ts">
import {onMounted, ref, computed} from "vue";
import {useToast} from "primevue";
import Select from "primevue/select";
import {OrderList} from "@/widgets/order-list";
import {orderApi, type OrderModel} from "@/entities/order";
import {useUserStore} from "@/entities/user";

const {actions: userActions} = useUserStore()
const isLoading = ref(false);
const toast = useToast();
const orders = ref<OrderModel[]>([])
const selectedStatus = ref<string | null>(null)
const statusOptions = ref([
  {label: 'Все заказы', value: null},
  {label: 'Ожидают', value: 'pending'},
  {label: 'В обработке', value: 'processing'},
  {label: 'Завершённые', value: 'completed'},
  {label: 'Отменённые', value: 'cancelled'},
])
const loadOrders = async () => {
  try {
    isLoading.value = true
    const res = await orderApi.getAllOrders()
    orders.value = res.data
    isLoading.value = false
  } catch (e) {
    toast.add({
      severity: 'error',
      summary: 'Ошибка',
      detail: 'Не удалось загрузить заказы',
      life: 3000,
    })
  }
}
const filteredOrders = computed(() => {
  if (!selectedStatus.value) return orders.value
  return orders.value.filter(order => order.status === selectedStatus.value)
})


onMounted(() => {
  loadOrders()
  userActions.init()
})
</script>

<template>
  <div class="min-h-svh  py-8">
    <div class="container mx-auto px-4 max-w-4xl">
      <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
          <h1 class="text-3xl font-bold text-gray-50">Мои заказы</h1>
          <p class="text-gray-500 mt-1">История ваших покупок</p>
        </div>

        <div class="flex items-center gap-3">
          <Select
            v-model="selectedStatus"
            :options="statusOptions"
            optionLabel="label"
            optionValue="value"
            placeholder="Фильтр"
            class="w-44"
          />
          <Button
            icon="pi pi-refresh"
            severity="secondary"
            outlined
            :loading="isLoading"
            @click="loadOrders"
          />
        </div>
      </div>

      <div v-if="!isLoading && orders.length > 0"
           class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class=" rounded-lg p-4 shadow text-center">
          <p class="text-3xl font-bold text-primary">{{ orders.length }}</p>
          <p class="text-sm text-gray-500">Всего заказов</p>
        </div>
        <div class=" rounded-lg p-4 shadow text-center">
          <p class="text-3xl font-bold text-yellow-500">
            {{ orders.filter(o => o.status === 'pending').length }}
          </p>
          <p class="text-sm text-gray-500">Ожидают</p>
        </div>
        <div class=" rounded-lg p-4 shadow text-center">
          <p class="text-3xl font-bold text-blue-500">
            {{ orders.filter(o => o.status === 'processing').length }}
          </p>
          <p class="text-sm text-gray-500">В обработке</p>
        </div>
        <div class=" rounded-lg p-4 shadow text-center">
          <p class="text-3xl font-bold text-green-500">
            {{ orders.filter(o => o.status === 'completed').length }}
          </p>
          <p class="text-sm text-gray-500">Завершённые</p>
        </div>
      </div>
      <OrderList
        :orders="filteredOrders"
        :loading="isLoading"
        @refresh="loadOrders"
      />
    </div>
  </div>
</template>

<style scoped>

</style>
