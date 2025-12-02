<script setup lang="ts">
import {ref} from 'vue'
import Dialog from 'primevue/dialog'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import Textarea from 'primevue/textarea'
import Dropdown from 'primevue/dropdown'
import Checkbox from 'primevue/checkbox'
import FileUpload from 'primevue/fileupload'
import type {CategoryModel} from '@/entities/category'

const props = defineProps<{
  visible: boolean
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
    categoryId: number
    isActive: boolean
    image: File | null
  }]
}>()

const formData = ref({
  name: '',
  description: null as string | null,
  price: 0,
  stock: 0,
  categoryId: 0,
  isActive: true,
  image: null as File | null
})

const handleImageUpload = (event: any) => {
  const files = event.files
  if (files && files.length > 0) {
    formData.value.image = files[0]
  }
}

const handleImageRemove = () => {
  formData.value.image = null
}

const handleSave = () => {
  if (!formData.value.name || !formData.value.categoryId || formData.value.price <= 0) {
    return
  }
  emit('save', formData.value)
}

const resetForm = () => {
  formData.value = {
    name: '',
    description: null,
    price: 0,
    stock: 0,
    categoryId: 0,
    isActive: true,
    image: null
  }
}

const handleDialogHide = () => {
  resetForm()
  emit('update:visible', false)
}
</script>

<template>
  <Dialog
    :visible="visible"
    @update:visible="handleDialogHide"
    header="Создание нового товара"
    :style="{ width: '600px' }"
    modal
  >
    <div class="flex flex-col gap-4">
      <div class="flex flex-col gap-2">
        <label for="name" class="font-medium">Название <span class="text-red-500">*</span></label>
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
          <label for="price" class="font-medium">Цена <span class="text-red-500">*</span></label>
          <InputNumber
            id="price"
            v-model="formData.price"
            mode="currency"
            currency="KZT"
            locale="ru-RU"
            :min="0"
          />
        </div>

        <div class="flex flex-col gap-2">
          <label for="stock" class="font-medium">Остаток <span class="text-red-500">*</span></label>
          <InputNumber
            id="stock"
            v-model="formData.stock"
            :min="0"
          />
        </div>
      </div>

      <div class="flex flex-col gap-2">
        <label for="category" class="font-medium">Категория <span class="text-red-500">*</span></label>
        <Dropdown
          id="category"
          v-model="formData.categoryId"
          :options="categories"
          optionLabel="name"
          optionValue="id"
          placeholder="Выберите категорию"
        />
      </div>

      <div class="flex flex-col gap-2">
        <label for="image" class="font-medium">Изображение товара</label>
        <FileUpload
          mode="basic"
          accept="image/*"
          :maxFileSize="2000000"
          :auto="true"
          chooseLabel="Выбрать изображение"
          @select="handleImageUpload"
          @remove="handleImageRemove"
        />
        <small class="text-gray-500">Максимальный размер: 2MB. Форматы: JPEG, PNG, GIF, WebP</small>
        <div v-if="formData.image" class="text-sm text-green-600">
          Выбрано: {{ formData.image.name }}
        </div>
      </div>

      <div class="flex items-center gap-2">
        <Checkbox
          id="is_active"
          v-model="formData.isActive"
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
        @click="handleDialogHide"
      />
      <Button
        label="Создать"
        severity="success"
        :loading="loading"
        :disabled="!formData.name || !formData.categoryId || formData.price <= 0"
        @click="handleSave"
      />
    </template>
  </Dialog>
</template>