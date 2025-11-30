<script setup lang="ts">

import {NavbarUi} from "@/widgets/navbar";
import {FooterUi} from "@/widgets/footer";
</script>

<template>
  <div>
    <NavbarUi/>
    <slot></slot>
    <FooterUi/>
  </div>
</template>

<style scoped>

</style>
