<script setup lang="ts">
import {ref, watch} from 'vue'
import {useRoute, useRouter} from 'vue-router'
import TabMenu from 'primevue/tabmenu'

const router = useRouter()
const route = useRoute()

const menuItems = [
  {label: 'Товары', icon: 'pi pi-box', route: '/admin/dashboard'},
  {label: 'Заказы', icon: 'pi pi-shopping-bag', route: '/admin/orders'},
]

const activeIndex = ref(0)

watch(() => route.path, (newPath) => {
  const index = menuItems.findIndex(item => item.route === newPath)
  if (index !== -1) {
    activeIndex.value = index
  }
}, {immediate: true})

const onTabChange = (event: any) => {
  const selectedItem = menuItems[event.index]
  if (selectedItem) {
    router.push(selectedItem.route)
  }
}
</script>

<template>
  <div class="mb-4">
    <TabMenu
      :model="menuItems"
      :activeIndex="activeIndex"
      @tab-change="onTabChange"
    />
  </div>
</template>
