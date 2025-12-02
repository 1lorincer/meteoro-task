import {$http} from "@/shared/api/http.ts";

class AdminOrdersApi {
  private basePath = '/admin/orders'

  async getAllOrders(): Promise<void> {
    return (await $http.get(this.basePath)).data
  }

  async getOrderById(id: number): Promise<void> {
    return (await $http.get(`${this.basePath}/${id}`)).data``
  }

  async updateOrderStatus(id: number, status: string): Promise<void> {
    return (await $http.patch(`${this.basePath}/${id}/status`, {status})).data
  }
}

export const adminOrdersApi = new AdminOrdersApi()
