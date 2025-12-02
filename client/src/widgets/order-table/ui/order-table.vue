<script setup lang="ts">
import {ref, computed} from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import Tag from 'primevue/tag'
import Skeleton from 'primevue/skeleton'
import InputText from 'primevue/inputtext'
import Dropdown from 'primevue/dropdown'
import type {OrderModel, StatusType} from '@/entities/order'
import {ORDER_STATUS_MAP} from '@/entities/order'
import {formatPrice} from '@/shared/lib/helpers/formatPrice'

interface IProps {
  orders: OrderModel[]
  loading: boolean
}

const props = defineProps<IProps>()

const emit = defineEmits<{
  editStatus: [order: OrderModel]
  viewDetails: [order: OrderModel]
}>()

const globalFilter = ref('')
const statusFilter = ref<StatusType | null>(null)

const statusOptions = [
  {label: 'Все статусы', value: null},
  {label: 'Ожидает', value: 'pending'},
  {label: 'В обработке', value: 'processing'},
  {label: 'Завершён', value: 'completed'},
  {label: 'Отменён', value: 'cancelled'},
]

const filteredOrders = computed(() => {
  let result = props.orders

  if (statusFilter.value) {
    result = result.filter(order => order.status === statusFilter.value)
  }

  return result
})

const getStatusInfo = (status: StatusType) => {
  return ORDER_STATUS_MAP[status]
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <div class="flex gap-4 items-center">
      <div class="flex-1 relative">
        <i class="pi pi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"/>
        <InputText
          v-model="globalFilter"
          placeholder="Поиск по ID, телефону или адресу..."
          class="w-full pl-10"
        />
      </div>
      <Dropdown
        v-model="statusFilter"
        :options="statusOptions"
        optionLabel="label"
        optionValue="value"
        placeholder="Фильтр по статусу"
        class="w-64"
      />
    </div>

    <DataTable
      :value="filteredOrders"
      :loading="loading"
      :globalFilter="globalFilter"
      paginator
      :rows="10"
      :rowsPerPageOptions="[5, 10, 20, 50]"
      dataKey="id"
      stripedRows
      :globalFilterFields="['id', 'phone', 'address']"
    >
      <template #loading>
        <div class="flex flex-col gap-2">
          <Skeleton height="3rem" v-for="i in 5" :key="i"/>
        </div>
      </template>

      <template #empty>
        <div class="text-center p-4 text-gray-500">
          Заказы не найдены
        </div>
      </template>

      <Column field="id" header="ID" :sortable="true" style="min-width: 80px">
        <template #body="slotProps">
          <span class="font-semibold">#{{ slotProps.data.id }}</span>
        </template>
      </Column>

      <Column field="user_id" header="ID Пользователя" :sortable="true" style="min-width: 120px"/>

      <Column field="status" header="Статус" :sortable="true" style="min-width: 150px">
        <template #body="slotProps">
          <Tag
            :value="getStatusInfo(slotProps.data.status).label"
            :severity="getStatusInfo(slotProps.data.status).severity"
            :icon="getStatusInfo(slotProps.data.status).icon"
          />
        </template>
      </Column>

      <Column field="total" header="Сумма" :sortable="true" style="min-width: 120px">
        <template #body="slotProps">
          <span class="font-semibold">{{ formatPrice(slotProps.data.total) }}</span>
        </template>
      </Column>

      <Column field="phone" header="Телефон" style="min-width: 150px">
        <template #body="slotProps">
          <div class="flex items-center gap-2">
            <i class="pi pi-phone text-gray-500"/>
            <span>{{ slotProps.data.phone }}</span>
          </div>
        </template>
      </Column>

      <Column field="address" header="Адрес" style="min-width: 200px">
        <template #body="slotProps">
          <div class="flex items-center gap-2">
            <i class="pi pi-map-marker text-gray-500"/>
            <span class="truncate max-w-xs">{{ slotProps.data.address }}</span>
          </div>
        </template>
      </Column>

      <Column field="items" header="Товаров" style="min-width: 100px">
        <template #body="slotProps">
          <span class="text-gray-600">{{ slotProps.data.items.length }}</span>
        </template>
      </Column>

      <Column header="Действия" style="min-width: 150px">
        <template #body="slotProps">
          <div class="flex gap-2">
            <Button
              icon="pi pi-eye"
              severity="info"
              text
              rounded
              v-tooltip.top="'Подробнее'"
              @click="emit('viewDetails', slotProps.data)"
            />
            <Button
              icon="pi pi-pencil"
              severity="warning"
              text
              rounded
              v-tooltip.top="'Изменить статус'"
              @click="emit('editStatus', slotProps.data)"
            />
          </div>
        </template>
      </Column>
    </DataTable>
  </div>
</template>

<style scoped>
</style>