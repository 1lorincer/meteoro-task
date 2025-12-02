<script setup lang="ts">
import Dialog from 'primevue/dialog'
import Button from 'primevue/button'
import Tag from 'primevue/tag'
import Divider from 'primevue/divider'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import type {OrderModel} from '@/entities/order'
import {ORDER_STATUS_MAP} from '@/entities/order'
import {formatPrice} from '@/shared/lib/helpers/formatPrice'
import {getImageUrl} from '@/shared/lib/helpers/getImageUrl'

const props = defineProps<{
  visible: boolean
  order: OrderModel | null
}>()

const emit = defineEmits<{
  'update:visible': [value: boolean]
}>()
</script>

<template>
  <Dialog
    :visible="visible"
    @update:visible="emit('update:visible', $event)"
    header="Детали заказа"
    :style="{ width: '800px' }"
    modal
  >
    <div v-if="order" class="flex flex-col gap-4">
      <!-- Order Info -->
      <div class="grid grid-cols-2 gap-4">
        <div class="p-4  rounded-lg">
          <div class="text-sm text-gray-600 mb-1">Номер заказа</div>
          <div class="text-xl font-semibold">#{{ order.id }}</div>
        </div>
        <div class="p-4  rounded-lg">
          <div class="text-sm text-gray-600 mb-1">Статус</div>
          <Tag
            :value="ORDER_STATUS_MAP[order.status].label"
            :severity="ORDER_STATUS_MAP[order.status].severity"
            :icon="ORDER_STATUS_MAP[order.status].icon"
          />
        </div>
      </div>

      <!-- Customer Info -->
      <div class="p-4 border border-gray-200 rounded-lg">
        <h3 class="font-semibold mb-3 text-lg">Информация о клиенте</h3>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <div class="text-sm text-gray-600">ID Пользователя</div>
            <div class="font-medium">{{ order.user_id }}</div>
          </div>
          <div>
            <div class="text-sm text-gray-600">Телефон</div>
            <div class="font-medium flex items-center gap-2">
              <i class="pi pi-phone text-gray-500"/>
              {{ order.phone }}
            </div>
          </div>
          <div class="col-span-2">
            <div class="text-sm text-gray-600">Адрес доставки</div>
            <div class="font-medium flex items-center gap-2">
              <i class="pi pi-map-marker text-gray-500"/>
              {{ order.address }}
            </div>
          </div>
          <div class="col-span-2" v-if="order.comment">
            <div class="text-sm text-gray-600">Комментарий</div>
            <div class="font-medium">{{ order.comment }}</div>
          </div>
        </div>
      </div>

      <Divider/>

      <!-- Order Items -->
      <div>
        <h3 class="font-semibold mb-3 text-lg">Товары в заказе</h3>
        <DataTable :value="order.items" stripedRows>
          <Column field="product_id" header="ID Товара" style="width: 100px"/>

          <Column header="Изображение" style="width: 100px">
            <template #body="slotProps">
              <img
                v-if="slotProps.data.product?.image && typeof slotProps.data.product.image === 'string'"
                :src="getImageUrl(slotProps.data.product.image)"
                :alt="slotProps.data.product?.name"
                class="w-16 h-16 object-cover rounded"
              />
              <div v-else class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                <i class="pi pi-image text-gray-400"/>
              </div>
            </template>
          </Column>

          <Column header="Название">
            <template #body="slotProps">
              <span class="font-medium">{{ slotProps.data.product?.name || 'Товар не найден' }}</span>
            </template>
          </Column>

          <Column field="quantity" header="Количество" style="width: 120px">
            <template #body="slotProps">
              <span class="font-medium">{{ slotProps.data.quantity }} шт.</span>
            </template>
          </Column>

          <Column field="price" header="Цена" style="width: 120px">
            <template #body="slotProps">
              {{ formatPrice(slotProps.data.price) }}
            </template>
          </Column>

          <Column header="Сумма" style="width: 120px">
            <template #body="slotProps">
              <span class="font-semibold">
                {{ formatPrice(slotProps.data.price * slotProps.data.quantity) }}
              </span>
            </template>
          </Column>
        </DataTable>
      </div>

      <Divider/>

      <div class="flex justify-end">
        <div class="p-4 bg-green-50 rounded-lg">
          <div class="text-sm text-gray-600 mb-1">Итого</div>
          <div class="text-2xl font-bold text-green-600">{{ formatPrice(order.total) }}</div>
        </div>
      </div>
    </div>

    <template #footer>
      <Button
        label="Закрыть"
        severity="secondary"
        @click="emit('update:visible', false)"
      />
    </template>
  </Dialog>
</template>
