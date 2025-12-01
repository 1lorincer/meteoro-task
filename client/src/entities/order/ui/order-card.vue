<script setup lang="ts">
import {computed, ref} from 'vue'
import Card from 'primevue/card'
import Tag from 'primevue/tag'
import Button from 'primevue/button'
import Divider from 'primevue/divider'
import {formatPrice} from '@/shared/lib/helpers/formatPrice.ts'
import {type OrderModel, ORDER_STATUS_MAP} from '../model/order-model.ts'

interface Props {
  order: OrderModel
}

const props = defineProps<Props>()

const isExpanded = ref(false)

const status = computed(() => ORDER_STATUS_MAP[props.order.status])

const itemsPreview = computed(() => {
  if (props.order.items.length <= 2) return props.order.items
  return props.order.items.slice(0, 2)
})

const hasMoreItems = computed(() => props.order.items.length > 2)
</script>

<template>
  <Card class="order-card">
    <template #content>

      <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
        <div>
          <div class="flex items-center gap-3 mb-1">
            <h3 class="text-xl font-bold">Заказ #{{ order.id }}</h3>
            <Tag
              :value="status.label"
              :severity="status.severity"
              :icon="status.icon"
            />
          </div>
        </div>
        <div class="text-right">
          <p class="text-sm text-gray-500">Сумма заказа</p>
          <p class="text-2xl font-bold text-primary">{{ formatPrice(order.total) }}</p>
        </div>
      </div>

      <Divider/>

      <div class="mb-4">
        <div class="flex items-center justify-between mb-3">
          <h4 class="font-semibold text-gray-700">
            <i class="pi pi-shopping-bag mr-2"></i>
            Товары ({{ order.items.length }})
          </h4>
          <Button
            v-if="hasMoreItems"
            :label="isExpanded ? 'Свернуть' : 'Показать все'"
            :icon="isExpanded ? 'pi pi-chevron-up' : 'pi pi-chevron-down'"
            severity="secondary"
            text
            size="small"
            @click="isExpanded = !isExpanded"
          />
        </div>

        <div class="space-y-3">
          <div
            v-for="item in (isExpanded ? order.items : itemsPreview)"
            :key="item.id"
            class="flex gap-4 p-3  rounded-lg"
          >
            <img
              :src="item.product?.image || '/placeholder.png'"
              :alt="item.product?.name"
              class="w-16 h-16 object-cover rounded-lg"
            />
            <div class="flex-1 min-w-0">
              <p class="text-sm text-gray-500">
                {{ item.quantity }} шт. × {{ formatPrice(item.price) }}
              </p>
            </div>
            <p class="font-semibold whitespace-nowrap">
              {{ formatPrice(item.price * item.quantity) }}
            </p>
          </div>

          <p
            v-if="hasMoreItems && !isExpanded"
            class="text-sm text-gray-500 text-center py-2"
          >
            И ещё {{ order.items.length - 2 }} товар(ов)...
          </p>
        </div>
      </div>

      <Divider/>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div class="flex items-start gap-3">
          <i class="pi pi-map-marker text-primary mt-1"></i>
          <div>
            <p class="text-gray-500">Адрес доставки</p>
            <p class="font-medium">{{ order.address }}</p>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <i class="pi pi-phone text-primary mt-1"></i>
          <div>
            <p class="text-gray-500">Телефон</p>
            <p class="font-medium">{{ order.phone }}</p>
          </div>
        </div>
        <div v-if="order.comment" class="md:col-span-2 flex items-start gap-3">
          <i class="pi pi-comment text-primary mt-1"></i>
          <div>
            <p class="text-gray-500">Комментарий</p>
            <p class="font-medium">{{ order.comment }}</p>
          </div>
        </div>
      </div>
    </template>
  </Card>
</template>

<style scoped>
.order-card {
  transition: box-shadow 0.2s ease;
}

.order-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
</style>
