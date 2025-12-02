import {$http} from "@/shared/api/http.ts";
import type {
  AdminProductById,
  AdminProductDto,
  AdminProductResponse,
  CreateAdminProductResponse,
  UpdateAdminProductDto
} from "@/entities/admin/products";
import type {IMessage} from "@/features/auth";

class AdminProductsApi {
  private basePath = '/admin/products'

  async getAllProducts(): Promise<AdminProductResponse> {
    return (await $http.get(this.basePath)).data
  }

  async createProduct(dto: AdminProductDto): Promise<CreateAdminProductResponse> {
    const formData = new FormData()
    formData.append('category_id', dto.category_id.toString())
    formData.append('name', dto.name)
    formData.append('description', dto.description || '')
    formData.append('price', dto.price.toString())
    formData.append('stock', dto.stock.toString())
    formData.append('is_active', dto.is_active ? '1' : '0')

    if (dto.image) {
      formData.append('image', dto.image)
    }

    return (await $http.post(this.basePath, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })).data
  }

  async getProductById(id: number): Promise<AdminProductById> {
    return (await $http.get(`${this.basePath}/${id}`)).data
  }

  async updateProduct(id: number, dto: UpdateAdminProductDto): Promise<IMessage> {
    const formData = new FormData()
    formData.append('_method', 'PUT')
    formData.append('category_id', dto.category_id.toString())
    formData.append('name', dto.name)
    formData.append('description', dto.description || '')
    formData.append('price', dto.price.toString())
    formData.append('stock', dto.stock.toString())
    formData.append('is_active', dto.is_active ? '1' : '0')

    if (dto.image && dto.image instanceof File) {
      formData.append('image', dto.image)
    }

    return (await $http.post(`${this.basePath}/${id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })).data
  }

  async deleteProduct(id: number): Promise<IMessage> {
    return (await $http.delete(`${this.basePath}/${id}`)).data
  }
}

export const adminProductsApi = new AdminProductsApi()
