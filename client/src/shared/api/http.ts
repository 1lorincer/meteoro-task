import axios, {AxiosError} from "axios";
import type {ApiError, ValidationError} from './types'
import {getToken} from "@/shared/lib/helpers/getToken.ts";
import {useRouter} from "vue-router";

const router = useRouter()
export const $http = axios.create({
  baseURL: import.meta.env.VITE_API_URL || '/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
});


$http.interceptors.request.use((config) => {
  const token = getToken()

  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }

  return config
})

$http.interceptors.response.use(
  (response) => response,
  async (error: AxiosError<ValidationError | ApiError>) => {
    const status = error.response?.status

    if (status === 401) {
      await router.push('/login')
      return Promise.reject(error)
    }

    if (status === 403) {
      await router.push('/forbidden')
      console.error('Доступ запрещён')
    }

    if (status === 422) {
      return Promise.reject(error)
    }

    if (status && status >= 500) {
      console.error('Ошибка сервера:', error.response?.data?.message)
    }

    return Promise.reject(error)
  }
)
