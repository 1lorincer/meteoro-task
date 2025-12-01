import type {Product} from "@/entities/product";

export interface CartItem {
  quantity: number;
  product: Product
}
