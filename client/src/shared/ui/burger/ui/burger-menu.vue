<script setup lang="ts">

</script>

<template>
  <div class="w-6 h-6 flex flex-col gap-1.5 cursor-pointer">
    <div v-for="el in 3" :key="`burger-el-${el}`" class="w-full h-0.5 bg-white rounded-2xl"></div>
  </div>
</template>

<style scoped>

</style>
