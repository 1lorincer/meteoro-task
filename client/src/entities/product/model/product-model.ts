import type {CategoryModel} from "@/entities/category";

export interface Product {
  id: number //
  category_id: number
  name: string
  slug: string
  description: string | null
  price: number
  stock: number;
  image: File | string | null;
  is_active: boolean
  category: CategoryModel //
  created_at: string //
  updated_at: string //
}
export interface ProductListParams {
  category_id?: number
  search?: string
  sort_by?: 'price' | 'name' | 'created_at'
  sort_order?: 'asc' | 'desc'
  page?: number
  per_page?: number
}
