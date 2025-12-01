<script setup lang="ts">
import {ref, onMounted} from 'vue'
import {useRoute, useRouter} from 'vue-router'
import {productApi} from '@/entities/product'
import type {Product} from '@/entities/product'
import Card from 'primevue/card'
import Button from 'primevue/button'
import ProgressSpinner from 'primevue/progressspinner'
import Tag from 'primevue/tag'
import Divider from 'primevue/divider'
import InputNumber from 'primevue/inputnumber'
import Image from 'primevue/image'
import Breadcrumb from 'primevue/breadcrumb'
import {useToast} from 'primevue/usetoast'
import {formatPrice} from "@/shared/lib/helpers/formatPrice.ts";
import {useCartStore} from "@/features/cart";

const route = useRoute()
const router = useRouter()
const toast = useToast()
const cartStore = useCartStore()
const product = ref<Product | null>(null)
const loading = ref(true)
const quantity = ref(1)

const breadcrumbItems = ref([
  {label: 'Каталог', to: '/'},
  {label: '', to: ''}
])

const loadProduct = async () => {
  try {
    loading.value = true
    if (route.params.id) {
      product.value = await productApi.getById(+route.params.id)
      breadcrumbItems.value[1] = {
        label: product.value.name,
        to: `/catalog/product/${product.value.id}`
      }
    }
  } catch (error) {
    console.error('Ошибка загрузки продукта:', error)
    toast.add({
      severity: 'error',
      summary: 'Ошибка',
      detail: 'Не удалось загрузить товар',
      life: 3000
    })
  } finally {
    loading.value = false
  }
}

const handleOrder = () => {
  cartStore.actions.addItem(product.value!, quantity.value)
  toast.add({
    severity: 'success',
    summary: 'Товар добавлен',
    detail: `${product.value?.name} (${quantity.value} шт.) добавлен в корзину`,
    life: 3000
  })
}


const getStockSeverity = (stock: number) => {
  if (stock === 0) return 'danger'
  if (stock < 10) return 'warn'
  return 'success'
}

const getStockLabel = (stock: number) => {
  if (stock === 0) return 'Нет в наличии'
  if (stock < 10) return `Осталось ${stock} шт.`
  return 'В наличии'
}

onMounted(() => {
  loadProduct()
})
</script>

<template>
  <div class="product-page">
    <Breadcrumb :model="breadcrumbItems" class="mb-4">
      <template #item="{item}">
        <router-link v-if="item.to" :to="item.to" class="p-menuitem-link">
          <span>{{ item.label }}</span>
        </router-link>
      </template>
    </Breadcrumb>

    <div v-if="loading" class="flex justify-center items-center h-svh">
      <ProgressSpinner/>
    </div>

    <Card v-else-if="product" class="product-card">
      <template #content>
        <div class="">
          <div class="col-12 md:col-6">
            <div class="product-image-container">
              <Image
                v-if="product.image"
                :src="product.image"
                :alt="product.name"
                preview
                class="product-image"
              />
              <div v-else class="no-image">
                <p class="text-surface-500">Изображение отсутствует</p>
              </div>
            </div>
          </div>

          <div class="col-12 md:col-6">
            <div class="product-details">
              <h1 class="product-title">{{ product.name }}</h1>

              <div class="flex align-items-center gap-2 mb-3">
                <Tag
                  :value="product.category.name"
                  severity="info"
                  icon="pi pi-tag"
                />
                <Tag
                  :value="getStockLabel(product.stock)"
                  :severity="getStockSeverity(product.stock)"
                  icon="pi pi-box"
                />
                <Tag
                  v-if="product.is_active"
                  value="Активен"
                  severity="success"
                  icon="pi pi-check-circle"
                />
              </div>

              <Divider/>

              <div class="product-description mb-4">
                <h3>Описание</h3>
                <p v-if="product.description">{{ product.description }}</p>
                <p v-else class="text-surface-500">Описание отсутствует</p>
              </div>

              <Divider/>

              <div class="product-price mb-4">
                <span class="price-label">Цена:</span>
                <span class="price-value">{{ formatPrice(product.price) }}</span>
              </div>

              <div class="product-actions">
                <div class="quantity-selector mb-3">
                  <label for="quantity" class="block mb-2">Количество:</label>
                  <InputNumber
                    id="quantity"
                    v-model="quantity"
                    :min="1"
                    :max="product.stock"
                    :disabled="product.stock === 0"
                    showButtons
                    buttonLayout="horizontal"
                    incrementButtonIcon="pi pi-plus"
                    decrementButtonIcon="pi pi-minus"
                    class="w-full"
                  />
                </div>

                <div class="flex gap-2">
                  <Button
                    label="Добавить в корзину"
                    icon="pi pi-shopping-cart"
                    severity="secondary"
                    size="large"
                    :disabled="product.stock === 0 || !product.is_active"
                    @click="handleOrder"
                    class="flex-1"
                  />
                  <Button
                    icon="pi pi-heart"
                    severity="secondary"
                    size="large"
                    outlined
                    :disabled="!product.is_active"
                  />
                </div>

                <Button
                  label="Вернуться к каталогу"
                  icon="pi pi-arrow-left"
                  severity="secondary"
                  outlined
                  class="w-full mt-3"
                  @click="router.push('/')"
                />
              </div>

              <Divider/>

              <div class="product-meta">
                <div class="meta-item">
                  <i class="pi pi-calendar"></i>
                  <span>Добавлен: {{
                      new Date(product.created_at).toLocaleDateString('ru-RU')
                    }}</span>
                </div>
                <div class="meta-item" v-if="product.updated_at !== product.created_at">
                  <i class="pi pi-refresh"></i>
                  <span>Обновлен: {{
                      new Date(product.updated_at).toLocaleDateString('ru-RU')
                    }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
    </Card>

    <div v-else class="error-state">
      <Card>
        <template #content>
          <div class="text-center p-5">
            <i class="pi pi-exclamation-triangle"
               style="font-size: 3rem; color: var(--red-500)"></i>
            <h2>Товар не найден</h2>
            <p class="text-surface-600">К сожалению, запрашиваемый товар не найден</p>
            <Button
              label="Вернуться к каталогу"
              icon="pi pi-arrow-left"
              @click="router.push('/')"
              class="mt-3"
            />
          </div>
        </template>
      </Card>
    </div>
  </div>
</template>

<style scoped>
.product-page {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

.product-card {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.product-image-container {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--surface-50);
  border-radius: 8px;
  padding: 1rem;
}

.product-image {
  width: 100%;
  max-height: 500px;
  object-fit: contain;
  border-radius: 8px;
}

.no-image {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 400px;
  gap: 1rem;
}

.product-details {
  padding: 0 1rem;
}

.product-title {
  font-size: 2rem;
  font-weight: 700;
  margin: 0 0 1rem 0;
  color: var(--text-color);
}

.product-description h3 {
  font-size: 1.2rem;
  margin-bottom: 0.5rem;
  color: var(--text-color);
}

.product-description p {
  line-height: 1.6;
  color: var(--text-color-secondary);
}

.product-price {
  display: flex;
  align-items: baseline;
  gap: 1rem;
}

.price-label {
  font-size: 1.1rem;
  color: var(--text-color-secondary);
}

.price-value {
  font-size: 2rem;
  font-weight: 700;
  color: var(--primary-color);
}

.quantity-selector label {
  font-weight: 600;
  color: var(--text-color);
}

.product-meta {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--text-color-secondary);
  font-size: 0.9rem;
}

.meta-item i {
  color: var(--primary-color);
}

@media (max-width: 768px) {
  .product-page {
    padding: 1rem 0.5rem;
  }

  .product-title {
    font-size: 1.5rem;
  }

  .price-value {
    font-size: 1.5rem;
  }

  .product-details {
    padding: 0;
  }
}
</style>
