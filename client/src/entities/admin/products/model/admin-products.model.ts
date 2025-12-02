import type {Product} from "@/entities/product";

type removedKeysProduct = 'id' | 'createdAt' | 'updatedAt' | 'slug' | 'category'

export interface AdminProductDto extends Omit<Product, removedKeysProduct> {
}

export interface CreateAdminProductResponse {
  id: number;
  category_id: number;
  name: string;
  price: number
}

export interface AdminProductResponse {
  data: Product[]
}

export interface AdminProductById {
  id: number;
  category_id: number;
  name: string;
  price: number
}

export interface UpdateAdminProductDto {
  category_id: number
  name: string
  description: string
  price: number
  stock: number
  is_active: boolean
  image?: File | string | null
}
