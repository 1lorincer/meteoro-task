<script setup lang="ts">

import {NavbarUi} from "@/widgets/navbar";
import {FooterUi} from "@/widgets/footer";
import Toast from 'primevue/toast';
</script>

<template>
  <div>
    <Toast />
    <NavbarUi/>
      <div class="container mx-auto px-5 mt-5">
        <slot></slot>
      </div>
    <FooterUi/>
  </div>
</template>

<style scoped>

</style>
