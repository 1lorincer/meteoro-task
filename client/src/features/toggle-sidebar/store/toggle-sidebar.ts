import {ref} from "vue";
import {defineStore} from "pinia";

export type SidebarMode = 'menu' | 'cart';

export const useToggleSidebar = defineStore("toggleSidebar", () => {
  const isOpen = ref(false)
  const mode = ref<SidebarMode>('menu')

  const toggle = () => isOpen.value = !isOpen.value

  const open = (newMode: SidebarMode = 'menu') => {
    mode.value = newMode
    isOpen.value = true
  }

  const close = () => {
    isOpen.value = false
  }

  return {
    isOpen,
    mode,
    actions: {
      toggle,
      open,
      close
    }
  }
});
