import type {StatusType} from "@/shared/types/status-type.ts";

export interface OrderItemModel {
  id: number,
  product_id: number,
  quantity: number,
  price: number
}

interface Item {
  product_id: number;
  quantity: number;
}

export interface OrderDto {
  items: Item[]
  phone: string;
  address: string;
  comment: string;
}

export interface OrderModel {
  id: number;
  user_id: number,
  status: StatusType,
  total: number,
  phone: string,
  address: string,
  items: OrderItemModel[]
}

export type OrderById = Omit<OrderModel, "items">

export interface OrderResponse {
  data: OrderModel[]
}
