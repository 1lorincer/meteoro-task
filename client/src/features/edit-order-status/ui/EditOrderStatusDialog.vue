<script setup lang="ts">
import {ref, watch} from 'vue'
import Dialog from 'primevue/dialog'
import Button from 'primevue/button'
import Dropdown from 'primevue/dropdown'
import type {OrderModel, StatusType} from '@/entities/order'
import {ORDER_STATUS_MAP} from '@/entities/order'

const props = defineProps<{
  visible: boolean
  order: OrderModel | null
  loading?: boolean
}>()

const emit = defineEmits<{
  'update:visible': [value: boolean]
  save: [orderId: number, status: StatusType]
}>()

const selectedStatus = ref<StatusType>('pending')

const statusOptions = [
  {label: ORDER_STATUS_MAP.pending.label, value: 'pending' as StatusType},
  {label: ORDER_STATUS_MAP.processing.label, value: 'processing' as StatusType},
  {label: ORDER_STATUS_MAP.completed.label, value: 'completed' as StatusType},
  {label: ORDER_STATUS_MAP.cancelled.label, value: 'cancelled' as StatusType},
]

watch(() => props.order, (order) => {
  if (order) {
    selectedStatus.value = order.status
  }
}, {immediate: true})

const handleSave = () => {
  if (props.order) {
    emit('save', props.order.id, selectedStatus.value)
  }
}
</script>

<template>
  <Dialog
    :visible="visible"
    @update:visible="emit('update:visible', $event)"
    header="Изменение статуса заказа"
    :style="{ width: '500px' }"
    modal
  >
    <div v-if="order" class="flex flex-col gap-4">
      <div class="p-4  rounded-lg">
        <div class="text-sm text-gray-600 mb-2">Заказ</div>
        <div class="text-xl font-semibold">#{{ order.id }}</div>
      </div>

      <div class="flex flex-col gap-2">
        <label for="status" class="font-medium">Новый статус</label>
        <Dropdown
          id="status"
          v-model="selectedStatus"
          :options="statusOptions"
          optionLabel="label"
          optionValue="value"
          placeholder="Выберите статус"
          class="w-full"
        />
      </div>

      <div class="text-sm text-gray-500">
        Текущий статус: <strong>{{ ORDER_STATUS_MAP[order.status].label }}</strong>
      </div>
    </div>

    <template #footer>
      <Button
        label="Отмена"
        severity="secondary"
        text
        @click="emit('update:visible', false)"
      />
      <Button
        label="Сохранить"
        severity="success"
        :loading="loading"
        @click="handleSave"
      />
    </template>
  </Dialog>
</template>
