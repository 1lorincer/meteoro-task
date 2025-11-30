import {$http} from "@/shared/api/http.ts";
import type {UserModel} from "@/entities/user";

class UserApi {
  async getUser(): Promise<UserModel> {
    return (await $http.get('/user')).data
  }
}

export const userApi = new UserApi()
