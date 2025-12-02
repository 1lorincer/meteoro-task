import type {OrderModel} from "@/entities/order";
import type {StatusType} from "@/shared/types/status-type.ts";

export interface AdminOrdersModel extends OrderModel {
}

export interface UpdateAdminOrderDto {
  status: StatusType
}
