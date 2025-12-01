<script setup lang="ts">
import Select from 'primevue/select';

interface IProps {
  sortOptions: Record<string, string>[]
}

const {sortOptions} = defineProps<IProps>()

const selectedSort = defineModel('selectedSort')
</script>

<template>
  <div class="flex flex-col sm:flex-row items-center gap-3">
    <span class="text-sm font-medium">Сортировка:</span>
    <Select
      v-model="selectedSort"
      :options="sortOptions"
      option-label="label"
      option-value="value"
      placeholder="Выберите сортировку"
      class="!w-full sm:w-64"
    />
  </div>
</template>

<style scoped>

</style>
