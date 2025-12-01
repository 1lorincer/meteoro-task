<script setup lang="ts">
import {useRouter} from "vue-router";
import Button from "primevue/button";

const router = useRouter();
</script>

<template>
  <div class="flex justify-center flex-col items-center h-svh">
    <p class="text-8xl font-semibold">404</p>
    <span class="mt-3 block">Такой страницы не существует</span>
    <Button class="mt-3" @click="router.push('/')">Вернуться на главную страницу</Button>
  </div>
</template>

<style scoped>

</style>
