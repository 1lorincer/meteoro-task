<script setup lang="ts">
import Toast from 'primevue/toast';
import {NavbarUi} from "@/widgets/navbar";
import {FooterUi} from "@/widgets/footer";
import {SidebarMenu} from "@/widgets/sidebar";
</script>

<template>
  <div>
    <Toast />
    <NavbarUi/>
    <SidebarMenu />
      <div class="container mx-auto px-5 mt-5">
        <slot></slot>
      </div>
    <FooterUi/>
  </div>
</template>

<style scoped>

</style>
