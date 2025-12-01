<script setup lang="ts">
import Skeleton from "primevue/skeleton";
import Button from "primevue/button";
import {type OrderModel, OrderCard} from "@/entities/order";

interface IProps {
  orders: OrderModel[]
  loading: boolean
}

defineProps<IProps>()

const emit = defineEmits<{
  refresh: []
}>()

</script>

<template>
  <div class="order-list">
    <template v-if="loading">
      <div class="space-y-4">
        <div v-for="i in 3" :key="i" class="rounded-lg p-6 shadow">
          <div class="flex justify-between mb-4">
            <div>
              <Skeleton width="150px" height="1.5rem" class="mb-2"/>
              <Skeleton width="200px" height="1rem"/>
            </div>
            <Skeleton width="120px" height="2rem"/>
          </div>
          <Skeleton height="1px" class="my-4"/>
          <div class="space-y-3">
            <div v-for="j in 2" :key="j" class="flex gap-4">
              <Skeleton width="64px" height="64px" class="rounded-lg"/>
              <div class="flex-1">
                <Skeleton width="70%" height="1rem" class="mb-2"/>
                <Skeleton width="40%" height="0.875rem"/>
              </div>
              <Skeleton width="80px" height="1rem"/>
            </div>
          </div>
        </div>
      </div>
    </template>
    <template v-else-if="orders.length === 0">
      <div
        class="flex flex-col items-center justify-center py-20 text-center  rounded-lg shadow">
        <i class="pi pi-shopping-bag text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">У вас пока нет заказов</h3>
        <p class="text-gray-500 mb-6">Самое время что-нибудь заказать!</p>
        <Button
          label="Перейти в каталог"
          icon="pi pi-arrow-right"
          iconPos="right"
          @click="$router.push('/')"
        />
      </div>
    </template>
    <template v-else>
      <div class="space-y-4">
        <OrderCard
          v-for="order in orders"
          :key="order.id"
          :order="order"
        />
      </div>
    </template>
  </div>
</template>

<style scoped>

</style>
