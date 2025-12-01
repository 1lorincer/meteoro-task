import {computed, ref} from "vue";
import {defineStore} from "pinia";
import {userApi, type UserModel} from "@/entities/user";
import {RolesType} from "@/shared/const/roles.ts";
import {getToken} from "@/shared/lib/helpers/getToken.ts";

export const useUserStore = defineStore('user', () => {
  const user = ref<UserModel>({
    id: 0,
    email: "",
    name: "",
    role: RolesType.DEFAULT
  })
  const isAdmin = computed(() =>  user.value.role === RolesType.ADMIN)
  const isUser = computed(() => user.value.role === RolesType.USER)

  const isLoginUser = computed(() => !!getToken())

  const getUserData = async () => {
    try {
      const data = await userApi.getUser()
      user.value = data
    } catch (e) {
      console.error(e)
    }
  }
  return {
    isAdmin,
    isUser,
    user,
    getters: {
      isLoginUser,
      isUser,
      isAdmin
    },
    actions: {
      getUserData
    }
  }
})
