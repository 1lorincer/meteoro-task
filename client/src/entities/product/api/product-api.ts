import {$http} from "@/shared/api/http.ts";
import type {PaginatedResponse} from '@/shared/api/types'
import type {Product, ProductListParams} from '../model/product-model.ts'

class ProductApi {
  private basePath = '/products'

  async getAll(params?: ProductListParams): Promise<PaginatedResponse<Product>> {
    return (await $http.get<PaginatedResponse<Product>>(this.basePath, {params})).data
  }

  async getById(id: number): Promise<Product> {
    return (await $http.get<Product>(`${this.basePath}/${id}`)).data
  }
}

export const productApi = new ProductApi()
