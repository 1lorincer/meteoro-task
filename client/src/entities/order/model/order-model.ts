import type {StatusType} from "@/shared/types/status-type.ts";

export interface OrderItemModel {
  id: number,
  product_id: number,
  quantity: number,
  price: number
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
