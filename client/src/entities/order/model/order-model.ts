import type {StatusType} from "@/shared/types/status-type.ts";
import type {Product} from "@/entities/product";

export interface OrderItemModel {
  id: number,
  product_id: number,
  quantity: number,
  price: number
  product?: Product;
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
  comment: string,
  items: OrderItemModel[]
}

export type OrderById = Omit<OrderModel, "items">

export interface OrderResponse {
  data: OrderModel[]
}

export const ORDER_STATUS_MAP: Record<StatusType, { label: string; severity: string; icon: string }> = {
  pending: { label: 'Ожидает', severity: 'warn', icon: 'pi pi-clock' },
  processing: { label: 'В обработке', severity: 'info', icon: 'pi pi-spin pi-spinner' },
  completed: { label: 'Завершён', severity: 'success', icon: 'pi pi-check-circle' },
  cancelled: { label: 'Отменён', severity: 'danger', icon: 'pi pi-times-circle' },
}
