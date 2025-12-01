import {RolesType} from "@/shared/const/roles.ts";

export interface UserModel {
  id: number | null;
  name: string
  email: string
  role: RolesType.USER | RolesType.ADMIN | RolesType.DEFAULT
  email_verified_at?: string | null
  created_at?: string
  updated_at?: string
}
