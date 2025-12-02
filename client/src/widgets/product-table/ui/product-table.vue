<script setup lang="ts">
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Tag from "primevue/tag";
import Button from "primevue/button";
import type {Product} from "@/entities/product";
import {formatPrice} from "@/shared/lib/helpers/formatPrice.ts";
import {getImageUrl} from "@/shared/lib/helpers/getImageUrl";

defineProps<{
  products: Product[]
  loading: boolean
}>()

const emit = defineEmits<{
  edit: [product: Product]
  delete: [product: Product]
}>()

const getStockSeverity = (stock: number) => {
  if (stock === 0) return 'danger'
  if (stock < 10) return 'warn'
  return 'success'
}
</script>

<template>
  <DataTable
    :value="products"
    :loading="loading"
    paginator
    :rows="10"
    :rowsPerPageOptions="[10, 25, 50]"
    stripedRows
    responsiveLayout="scroll"
    class="p-datatable-dark"
  >
    <template #empty>
      <div class="text-center py-8 text-gray-400">
        <i class="pi pi-inbox text-4xl mb-2"></i>
        <p>Товары не найдены</p>
      </div>
    </template>

    <Column field="id" header="ID" sortable style="width: 80px"/>

    <Column field="name" header="Название" sortable>
      <template #body="{ data }">
        <div class="flex items-center gap-3">
          <img
            :src="getImageUrl(data.image) || '/placeholder.png'"
            :alt="data.name"
            class="w-10 h-10 rounded object-cover"
          />
          <div>
            <p class="font-medium">{{ data.name }}</p>
            <p class="text-sm text-gray-400">{{ data.category?.name }}</p>
          </div>
        </div>
      </template>
    </Column>

    <Column field="price" header="Цена" sortable>
      <template #body="{ data }">
        <span class="font-semibold">{{ formatPrice(data.price) }}</span>
      </template>
    </Column>

    <Column field="stock" header="Остаток" sortable>
      <template #body="{ data }">
        <Tag
          :value="`${data.stock} шт.`"
          :severity="getStockSeverity(data.stock)"
        />
      </template>
    </Column>

    <Column field="is_active" header="Статус" sortable>
      <template #body="{ data }">
        <Tag
          :value="data.is_active ? 'Активен' : 'Скрыт'"
          :severity="data.is_active ? 'success' : 'secondary'"
        />
      </template>
    </Column>

    <Column header="Действия" style="width: 150px">
      <template #body="{ data }">
        <div class="flex gap-2">
          <Button
            icon="pi pi-pencil"
            severity="info"
            text
            rounded
            @click="emit('edit', data)"
          />
          <Button
            icon="pi pi-trash"
            severity="danger"
            text
            rounded
            @click="emit('delete', data)"
          />
        </div>
      </template>
    </Column>
  </DataTable>
</template>

<style scoped>
:deep(.p-datatable-dark) {
  background: transparent;
}

:deep(.p-datatable .p-datatable-thead > tr > th) {
  background: rgba(255, 255, 255, 0.05);
  border-color: rgba(255, 255, 255, 0.1);
}

:deep(.p-datatable .p-datatable-tbody > tr) {
  background: transparent;
  border-color: rgba(255, 255, 255, 0.1);
}

:deep(.p-datatable .p-datatable-tbody > tr:hover) {
  background: rgba(255, 255, 255, 0.05);
}
</style>
