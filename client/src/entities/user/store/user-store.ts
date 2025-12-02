import {computed, ref} from "vue";
import {defineStore} from "pinia";
import {userApi, type UserModel} from "@/entities/user";
import {RolesType} from "@/shared/const/roles.ts";
import {getToken} from "@/shared/lib/helpers/getToken.ts";
import {authApi} from "@/features/auth";

export const useUserStore = defineStore('user', () => {
  const user = ref<UserModel>({
    id: 0,
    email: "",
    name: "",
    role: RolesType.DEFAULT,
    email_verified_at: null
  })
  const isAdmin = computed(() => user.value.role === RolesType.ADMIN)
  const isUser = computed(() => user.value.role === RolesType.USER)

  const isLoginUser = computed(() => !!user.value.id && !!getToken())

  const getUserData = async () => {
    try {
      const data = await userApi.getUser()
      user.value = data
    } catch (e) {
      console.error(e)
    }
  }
  const logout = async () => {
    const res = await authApi.logout()
    localStorage.removeItem('token');
    user.value = {
      id: null,
      name: '',
      email: '',
      role: RolesType.DEFAULT
    };
    return res
  }

  const init = async () => {
    if (!!getToken()) {
      await getUserData()
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
      getUserData,
      init,
      logout
    }
  }
})
