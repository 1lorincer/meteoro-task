import {computed, ref} from "vue";
import {defineStore} from "pinia";
import type {Product} from "@/entities/product";
import type {CartItem} from "../models/cart-model.ts"

export const useCartStore = defineStore('cartStore', () => {
  const items = ref<CartItem[]>([])
  const totalItems = computed(() =>
    items.value.reduce((sum, item) => sum + item.quantity, 0)
  )


  const totalPrice = computed(() =>
    items.value.reduce((sum, item) => sum + item.product.price * item.quantity, 0)
  )

  const isEmpty = computed(() => items.value.length === 0)

  const addItem = (product: Product, quantity = 1) => {
    const existing = items.value.find(item => item.product.id === product.id)

    if (existing) {
      existing.quantity += quantity
    } else {
      items.value.push({product, quantity})
    }
  }

  const removeItem = (productId: number) => {
    items.value = items.value.filter(item => item.product.id !== productId)
  }
  const updateQuantity = (productId: number, quantity: number) => {
    const item = items.value.find(item => item.product.id === productId)

    if (item) {
      if (quantity <= 0) {
        removeItem(productId)
      } else {
        item.quantity = quantity
      }
    }
  }
  const clear = () => {
    items.value = []
  }
  const getOrderItems = () => {
    return items.value.map(item => ({
      product_id: item.product.id,
      quantity: item.quantity,
    }))
  }

  return {
    items,
    actions: {
      getOrderItems,
      clear,
      updateQuantity,
      addItem
    },
    getters: {
      isEmpty,
      totalItems,
      totalPrice
    }
  }
}, {
  persist: true
})
