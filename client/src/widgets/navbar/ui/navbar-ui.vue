<script setup lang="ts">
import {ref} from "vue";
import {useRouter} from "vue-router";
import Menubar from "primevue/menubar";
import Button from "primevue/button";
import {useUserStore} from "@/entities/user";

const router = useRouter()
const items = ref([])
const {getters: userGetters} = useUserStore()
</script>

<template>
  <Menubar :model="items" class="py-5 px-6">
    <template #start>
      <router-link to="/" class="text-2xl font-semibold">
        Meteoro market
      </router-link>
    </template>
    <template #end>
      <Button v-if="userGetters.isLoginUser" severity="danger" label="Выйти"
              @click="router.push('/login')"/>
      <Button v-else severity="success" label="Войти" @click="router.push('/login')"/>
    </template>
  </Menubar>
</template>

<style scoped>

</style>
