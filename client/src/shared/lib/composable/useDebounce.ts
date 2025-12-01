import {ref, watch, type Ref} from 'vue'

export function useDebounce<T>(initialValue: T, delay = 300) {
  let timeout: ReturnType<typeof setTimeout>

  const value = ref(initialValue) as Ref<T>
  const debouncedValue = ref(initialValue) as Ref<T>
  const isPending = ref(false)

  watch(value, (newValue) => {
    isPending.value = true
    clearTimeout(timeout)

    timeout = setTimeout(() => {
      debouncedValue.value = newValue
      isPending.value = false
    }, delay)
  })

  const cancel = () => {
    clearTimeout(timeout)
    isPending.value = false
  }

  const flush = () => {
    clearTimeout(timeout)
    debouncedValue.value = value.value
    isPending.value = false
  }

  return {
    value,           // Оригинальное значение (меняется сразу)
    debouncedValue,  // Debounced значение (меняется с задержкой)
    isPending,       // Ждём ли debounce
    cancel,          // Отменить pending
    flush,           // Применить сразу
  }
}
