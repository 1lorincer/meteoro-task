<script setup lang="ts">
import {ref, watch, computed} from 'vue'
import Dialog from 'primevue/dialog'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import Textarea from 'primevue/textarea'
import Dropdown from 'primevue/dropdown'
import Checkbox from 'primevue/checkbox'
import FileUpload from 'primevue/fileupload'
import type {Product} from '@/entities/product'
import type {CategoryModel} from '@/entities/category'
import {getImageUrl} from '@/shared/lib/helpers/getImageUrl'

const props = defineProps<{
  visible: boolean
  product: Product | null
  categories: CategoryModel[]
  loading?: boolean
}>()

const emit = defineEmits<{
  'update:visible': [value: boolean]
  save: [data: {
    name: string
    description: string | null
    price: number
    stock: number
    category_id: number
    is_active: boolean
    image: File | string | null
  }]
}>()

const formData = ref({
  name: '',
  description: null as string | null,
  price: 0,
  stock: 0,
  category_id: 0,
  is_active: true,
  image: null as File | string | null
})

const currentImageUrl = ref<string | null | File>(null)

const isNewImageSelected = computed(() => {
  return formData.value.image instanceof File
})

watch(() => props.product, (product) => {
  if (product) {
    formData.value = {
      name: product.name,
      description: product.description,
      price: product.price,
      stock: product.stock,
      category_id: product.category_id,
      is_active: product.is_active,
      image: product.image
    }
    currentImageUrl.value = product.image
  }
}, {immediate: true})

const handleImageUpload = (event: any) => {
  const files = event.files
  if (files && files.length > 0) {
    formData.value.image = files[0]
  }
}

const handleImageRemove = () => {
  formData.value.image = currentImageUrl.value // Вернуть к текущему изображению
}

const handleSave = () => {
  emit('save', formData.value)
}
</script>

<template>
  <Dialog
    :visible="visible"
    @update:visible="emit('update:visible', $event)"
    header="Редактирование товара"
    :style="{ width: '600px' }"
    modal
  >
    <div class="flex flex-col gap-4">
      <div class="flex flex-col gap-2">
        <label for="name" class="font-medium">Название</label>
        <InputText
          id="name"
          v-model="formData.name"
          placeholder="Введите название товара"
        />
      </div>

      <div class="flex flex-col gap-2">
        <label for="description" class="font-medium">Описание</label>
        <Textarea
          id="description"
          v-model="formData.description"
          rows="4"
          placeholder="Введите описание товара"
        />
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div class="flex flex-col gap-2">
          <label for="price" class="font-medium">Цена</label>
          <InputNumber
            id="price"
            v-model="formData.price"
            mode="currency"
            currency="KZT"
            locale="ru-RU"
          />
        </div>

        <div class="flex flex-col gap-2">
          <label for="stock" class="font-medium">Остаток</label>
          <InputNumber
            id="stock"
            v-model="formData.stock"
            :min="0"
          />
        </div>
      </div>

      <div class="flex flex-col gap-2">
        <label for="category" class="font-medium">Категория</label>
        <Dropdown
          id="category"
          v-model="formData.category_id"
          :options="categories"
          optionLabel="name"
          optionValue="id"
          placeholder="Выберите категорию"
        />
      </div>

      <div class="flex flex-col gap-2">
        <label class="font-medium">Изображение товара</label>

        <div v-if="currentImageUrl && !isNewImageSelected" class="mb-2">
          <img
            :src="getImageUrl(currentImageUrl) || ''"
            alt="Current product image"
            class="w-32 h-32 object-cover rounded border border-gray-300"
          />
          <p class="text-sm text-gray-500 mt-1">Текущее изображение</p>
        </div>

        <FileUpload
          mode="basic"
          accept="image/*"
          :maxFileSize="2000000"
          :auto="true"
          chooseLabel="Выбрать новое изображение"
          @select="handleImageUpload"
          @remove="handleImageRemove"
        />

        <small class="text-gray-500">Максимальный размер: 2MB. Форматы: JPEG, PNG, GIF, WebP</small>

        <div v-if="isNewImageSelected && formData.image" class="text-sm text-green-600">
          Выбрано новое изображение: {{ (formData.image as File).name }}
        </div>
      </div>

      <div class="flex items-center gap-2">
        <Checkbox
          id="is_active"
          v-model="formData.is_active"
          :binary="true"
        />
        <label for="is_active" class="font-medium cursor-pointer">Товар активен</label>
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
