<script setup lang="ts">
import {onMounted, ref} from 'vue'
import {useRouter} from 'vue-router'
import {storeToRefs} from 'pinia'
import {useToast} from 'primevue/usetoast'
import Button from 'primevue/button'
import InputMask from 'primevue/inputmask'
import Textarea from 'primevue/textarea'
import Card from 'primevue/card'
import Divider from 'primevue/divider'
import Stepper from 'primevue/stepper'
import StepList from 'primevue/steplist'
import StepPanels from 'primevue/steppanels'
import Step from 'primevue/step'
import StepPanel from 'primevue/steppanel'
import Tag from 'primevue/tag'
import {useCartStore} from '@/features/cart'
import {orderApi} from "@/entities/order";
import {useUserStore} from '@/entities/user'
import {formatPrice} from "@/shared/lib/helpers/formatPrice.ts";

const router = useRouter()
const toast = useToast()

const {items} = storeToRefs(useCartStore())
const {getters: cartStoreGetters, actions: cartStoreActions} = useCartStore()

const {user} = storeToRefs(useUserStore())
const {actions: userActions} = useUserStore()

const loading = ref(false)
const activeStep = ref(1)

const form = ref({
  phone: '',
  address: '',
  comment: '',
})

const errors = ref({
  phone: '',
  address: '',
})


const validateForm = () => {
  let isValid = true
  errors.value = {phone: '', address: ''}

  if (!form.value.phone || form.value.phone.includes('_')) {
    errors.value.phone = 'Введите корректный номер телефона'
    isValid = false
  }

  if (!form.value.address || form.value.address.length < 10) {
    errors.value.address = 'Введите полный адрес доставки'
    isValid = false
  }

  return isValid
}

const goToConfirm = () => {
  if (validateForm()) {
    activeStep.value = 2
  }
}

const submitOrder = async () => {
  loading.value = true

  try {
    const orderData = {
      items: cartStoreActions.getOrderItems(),
      phone: form.value.phone,
      address: form.value.address,
      comment: form.value.comment || undefined,
    }

    const order = await orderApi.createOrder(orderData)

    cartStoreActions.clear()

    toast.add({
      severity: 'success',
      summary: 'Заказ оформлен! 🎉',
      detail: `Номер заказа: #${order.id}`,
      life: 5000,
    })

    router.push('/orders')
  } catch (error: any) {
    toast.add({
      severity: 'error',
      summary: 'Ошибка',
      detail: error.response?.data?.message || 'Не удалось оформить заказ',
      life: 5000,
    })
  } finally {
    loading.value = false
  }
}
onMounted(() => {
  userActions.init()
})
</script>

<template>
  <div class="min-h-screen  py-8">
    <div class="container mx-auto px-4 max-w-6xl">
      <div class="mb-8">
        <Button
          icon="pi pi-arrow-left"
          label="Вернуться в каталог"
          severity="secondary"
          text
          @click="router.push('/')"
          class="mb-4"
        />
        <h1 class="text-3xl font-bold text-white">Оформление заказа</h1>
        <p class="text-gray-500 mt-1">Заполните данные для доставки</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
          <Stepper v-model:value="activeStep" linear>
            <StepList>
              <Step :value="1">Данные доставки</Step>
              <Step :value="2">Подтверждение</Step>
            </StepList>

            <StepPanels>
              <StepPanel :value="1">
                <Card>
                  <template #content>
                    <div class="space-y-6">
                      <div v-if="user?.name">
                        <label class="block mb-2 font-medium text-gray-700">Получатель</label>
                        <div class="flex items-center gap-3 p-3  rounded-lg">
                          <i class="pi pi-user text-primary"></i>
                          <span>{{ user.name }}</span>
                          <Tag value="Вы" severity="info" class="ml-auto"/>
                        </div>
                      </div>

                      <div>
                        <label class="block mb-2 font-medium text-gray-700">
                          Телефон <span class="text-red-500">*</span>
                        </label>
                        <InputMask
                          v-model="form.phone"
                          mask="+7 (999) 999-99-99"
                          placeholder="+7 (___) ___-__-__"
                          class="w-full"
                          :class="{ 'p-invalid': errors.phone }"
                        />
                        <small v-if="errors.phone" class="text-red-500">{{ errors.phone }}</small>
                      </div>

                      <div>
                        <label class="block mb-2 font-medium text-gray-700">
                          Адрес доставки <span class="text-red-500">*</span>
                        </label>
                        <Textarea
                          v-model="form.address"
                          placeholder="Город, улица, дом, квартира, подъезд, этаж..."
                          rows="3"
                          class="w-full"
                          :class="{ 'p-invalid': errors.address }"
                        />
                        <small v-if="errors.address" class="text-red-500">{{
                            errors.address
                          }}</small>
                      </div>

                      <!-- Комментарий -->
                      <div>
                        <label class="block mb-2 font-medium text-gray-700">
                          Комментарий к заказу
                        </label>
                        <Textarea
                          v-model="form.comment"
                          placeholder="Время доставки, особые пожелания..."
                          rows="2"
                          class="w-full"
                        />
                      </div>

                      <div class="flex justify-end pt-4">
                        <Button
                          label="Продолжить"
                          icon="pi pi-arrow-right"
                          iconPos="right"
                          @click="goToConfirm"
                        />
                      </div>
                    </div>
                  </template>
                </Card>
              </StepPanel>

              <!-- Шаг 2: Подтверждение -->
              <StepPanel :value="2">
                <Card>
                  <template #content>
                    <div class="space-y-6">
                      <div class=" rounded-lg p-4">
                        <h3 class="font-semibold mb-3 flex items-center gap-2">
                          <i class="pi pi-map-marker text-primary"></i>
                          Данные доставки
                        </h3>
                        <div class="space-y-2 text-sm">
                          <p><span class="text-gray-500">Телефон:</span> {{ form.phone }}</p>
                          <p><span class="text-gray-500">Адрес:</span> {{ form.address }}</p>
                          <p v-if="form.comment">
                            <span class="text-gray-500">Комментарий:</span> {{ form.comment }}
                          </p>
                        </div>
                        <Button
                          label="Изменить"
                          severity="secondary"
                          text
                          size="small"
                          class="mt-2"
                          @click="activeStep = 1"
                        />
                      </div>

                      <div>
                        <h3 class="font-semibold mb-3 flex items-center gap-2">
                          <i class="pi pi-shopping-cart text-primary"></i>
                          Товары в заказе
                        </h3>
                        <div class="space-y-3">
                          <div
                            v-for="item in items"
                            :key="item.product.id"
                            class="flex gap-4 p-3  rounded-lg"
                          >
                            <img
                              :src="item.product.image || '/placeholder.png'"
                              :alt="item.product.name"
                              class="w-16 h-16 object-cover rounded"
                            />
                            <div class="flex-1">
                              <p class="font-medium">{{ item.product.name }}</p>
                              <p class="text-sm text-gray-500">{{ item.quantity }} шт.</p>
                            </div>
                            <p class="font-semibold">
                              {{
                                formatPrice(item.product.price * item.quantity)
                              }}
                            </p>
                          </div>
                        </div>
                      </div>

                      <div class="flex justify-between pt-4">
                        <Button
                          label="Назад"
                          icon="pi pi-arrow-left"
                          severity="secondary"
                          outlined
                          @click="activeStep = 1"
                        />
                        <Button
                          label="Подтвердить заказ"
                          icon="pi pi-check"
                          :loading="loading"
                          @click="submitOrder"
                        />
                      </div>
                    </div>
                  </template>
                </Card>
              </StepPanel>
            </StepPanels>
          </Stepper>
        </div>

        <div class="lg:col-span-1">
          <Card class="sticky top-4">
            <template #title>
              <div class="flex items-center gap-2">
                <i class="pi pi-receipt text-primary"></i>
                <span>Ваш заказ</span>
              </div>
            </template>
            <template #content>
              <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                <div
                  v-for="item in items"
                  :key="item.product.id"
                  class="flex justify-between text-sm"
                >
                  <div class="flex-1 pr-2">
                    <p class="truncate">{{ item.product.name }}</p>
                    <p class="text-gray-400">{{ item.quantity }} ×
                      {{ formatPrice(item.product.price) }}</p>
                  </div>
                  <span class="font-medium whitespace-nowrap">
                    {{ formatPrice(item.product.price * item.quantity) }}
                  </span>
                </div>
              </div>

              <Divider/>

              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Товаров:</span>
                  <span>{{ cartStoreGetters.totalItems }} шт.</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Доставка:</span>
                  <span class="text-green-600">Бесплатно</span>
                </div>
              </div>

              <Divider/>

              <div class="flex justify-between text-xl font-bold">
                <span>Итого:</span>
                <span class="text-primary">{{ formatPrice(cartStoreGetters.totalPrice) }}</span>
              </div>

              <div class="mt-6 p-3 bg-blue-50 rounded-lg text-sm">
                <div class="flex gap-2">
                  <i class="pi pi-info-circle text-blue-500 mt-0.5"></i>
                  <div>
                    <p class="font-medium text-blue-800">Безопасная оплата</p>
                    <p class="text-blue-600">Оплата при получении заказа</p>
                  </div>
                </div>
              </div>
            </template>
          </Card>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
:deep(.p-stepper-list) {
  padding: 0;
  margin-bottom: 1.5rem;
}

:deep(.p-card) {
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

:deep(.p-invalid) {
  border-color: #ef4444;
}
</style>
