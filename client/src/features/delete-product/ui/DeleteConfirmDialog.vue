<script setup lang="ts">
import Dialog from 'primevue/dialog'
import Button from 'primevue/button'

defineProps<{
  visible: boolean
  itemName: string
  loading?: boolean
}>()

const emit = defineEmits<{
  'update:visible': [value: boolean]
  confirm: []
}>()
</script>

<template>
  <Dialog
    :visible="visible"
    @update:visible="emit('update:visible', $event)"
    header="Подтверждение удаления"
    :style="{ width: '400px' }"
    modal
  >
    <div class="flex items-center gap-4">
      <i class="pi pi-exclamation-triangle text-4xl text-red-500"></i>
      <span>
        Вы уверены, что хотите удалить <strong>{{ itemName }}</strong>?
      </span>
    </div>

    <template #footer>
      <Button
        label="Отмена"
        severity="secondary"
        text
        @click="emit('update:visible', false)"
      />
      <Button
        label="Удалить"
        severity="danger"
        :loading="loading"
        @click="emit('confirm')"
      />
    </template>
  </Dialog>
</template>
