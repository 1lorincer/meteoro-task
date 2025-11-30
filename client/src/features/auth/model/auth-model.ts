import type {UserModel} from "@/entities/user";

export interface AuthDto {
  email: string;
  password: string;
}

export interface AuthResponse {
  token: string;
  user: UserModel;
}

export interface RegisterDto extends AuthDto {
  name: string;
  password_confirmation: string;
}
