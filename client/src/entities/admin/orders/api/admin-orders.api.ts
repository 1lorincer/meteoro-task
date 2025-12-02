import {$http} from "@/shared/api/http.ts";
import type {OrderResponse, OrderModel} from "@/entities/order";

class AdminOrdersApi {
  private basePath = '/admin/orders'

  async getAllOrders(): Promise<OrderResponse> {
    return (await $http.get(this.basePath)).data
  }

  async getOrderById(id: number): Promise<OrderModel> {
    return (await $http.get(`${this.basePath}/${id}`)).data
  }

  async updateOrderStatus(id: number, status: string): Promise<OrderModel> {
    return (await $http.patch(`${this.basePath}/${id}/status`, {status})).data
  }
}

export const adminOrdersApi = new AdminOrdersApi()
