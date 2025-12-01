import type {OrderById, OrderDto, OrderResponse} from "@/entities/order";
import {$http} from "@/shared/api/http.ts";

class OrderApi {
  async getAllOrders(): Promise<OrderResponse> {
    return (await $http.get('/orders')).data
  }

  async createOrder(dto: OrderDto): Promise<any> {
    return (await $http.post('/orders', dto)).data
  }

  async getOrderById(id: number): Promise<OrderById> {
    return (await $http.get(`/orders/${id}`)).data
  }
}

export const orderApi = new OrderApi()
