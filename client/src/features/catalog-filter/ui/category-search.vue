<script setup lang="ts">
import InputText from "primevue/inputtext";

const search = defineModel("search")
</script>

<template>
  <InputText class="w-full sm:w-2/5 md:w-1/5" placeholder="Поиск..." v-model="search"/>
</template>

<style scoped>

</style>
