import {$http} from "@/shared/api/http.ts";
import type {AuthDto, AuthResponse, IMessage, RegisterDto} from "@/features/auth";
import type {UserModel} from "@/entities/user";

class AuthApi {
  async login(data: AuthDto): Promise<AuthResponse> {
    return (await $http.post<AuthResponse>('/login', data)).data
  }

  async register(data: RegisterDto): Promise<AuthResponse> {
    return (await $http.post<AuthResponse>('/register', data)).data
  }

  async logout(): Promise<IMessage> {
    return (await $http.post('/logout')).data
  }

  async getUser(): Promise<UserModel> {
    return (await $http.get<UserModel>('/user')).data
  }
}

export const authApi = new AuthApi()
