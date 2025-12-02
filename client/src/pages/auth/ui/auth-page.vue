<script setup lang="ts">
import {computed} from "vue";
import {storeToRefs} from "pinia";
import {useToast} from "primevue";
import {useRoute, useRouter} from "vue-router";
import {z} from 'zod';
import {zodResolver} from '@primevue/forms/resolvers/zod';
import Form from '@primevue/forms/form';
import InputText from 'primevue/inputtext';
import Message from 'primevue/message';
import Button from 'primevue/button';
import type {FormSubmitEvent} from '@primevue/forms';
import {authApi, type AuthDto, type RegisterDto} from "@/features/auth";
import {useUserStore} from "@/entities/user";
import {RolesType} from "@/shared/const/roles.ts";

const route = useRoute()
const router = useRouter()
const toast = useToast();
const isLogin = computed(() => route.query.status !== 'signup');
const {user} = storeToRefs(useUserStore())
const initialValues = computed(() =>
  isLogin.value
    ? {email: '', password: ''}
    : {name: '', email: '', password: '', password_confirmation: ''}
);

const resolver = computed(() => zodResolver(isLogin.value ? loginSchema : signupSchema));
const signupSchema = z.object({
  name: z.string().min(1, 'Имя обязательно'),
  email: z.string().min(1, 'Email обязателен').email('Некорректный email'),
  password: z.string().min(6, 'Минимум 6 символов'),
  password_confirmation: z.string().min(1, 'Подтвердите пароль'),
}).refine((data) => data.password === data.password_confirmation, {
  message: 'Пароли не совпадают',
  path: ['password_confirmation'],
});

const loginSchema = z.object({
  email: z.string().min(1, 'Email обязателен').email('Некорректный email'),
  password: z.string().min(1, 'Пароль обязателен'),
});

const onFormSubmit = async ({valid, values}: FormSubmitEvent) => {
  if (valid) {
    try {
      if (isLogin.value) {
        const response = await authApi.login(values as AuthDto);
        console.log(response)
        localStorage.setItem('token', response.token);
        localStorage.setItem('role', response.user.role);
        user.value = response.user;
        toast.add({severity: 'success', summary: 'Вход выполнен', life: 3000});
        if (response.user.role === RolesType.ADMIN) {
          await router.push('/admin/dashboard')
        } else {
          await router.push('/orders')
        }
      } else {
        const response = await authApi.register(values as RegisterDto);
        localStorage.setItem('token', response.token);
        user.value = response.user;
        toast.add({severity: 'success', summary: 'Регистрация успешна', life: 3000});
        await router.push('/orders')
      }
    } catch (error) {
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: 'Не удалось выполнить операцию',
        life: 3000
      });
    }
  }
};

const toggleMode = () => {
  router.push({
    query: {status: isLogin.value ? 'signup' : 'login'}
  });
};
</script>


<template>
  <div class="flex justify-center items-center h-svh">
    <div class="shadow w-2/3 lg:w-1/3 min-h-fit border  px-4 sm:px-10 py-2 sm:py-5">
      <h1 class="text-center my-3 text-2xl font-semibold">
        {{ isLogin ? 'Авторизация' : 'Регистрация' }}
      </h1>
      <Form
        v-slot="$form"
        :key="route.query.status"
        :resolver="resolver"
        :initialValues="initialValues"
        @submit="onFormSubmit"
        class="flex flex-col gap-4 w-full"
      >
        <div v-if="!isLogin" class="flex flex-col gap-1">
          <InputText name="name" type="text" placeholder="Имя" fluid/>
          <Message v-if="$form.name?.invalid" severity="error" size="small" variant="simple">
            {{ $form.name.error?.message }}
          </Message>
        </div>

        <div class="flex flex-col gap-1">
          <InputText name="email" type="email" placeholder="Email" fluid/>
          <Message v-if="$form.email?.invalid" severity="error" size="small" variant="simple">
            {{ $form.email.error?.message }}
          </Message>
        </div>

        <div class="flex flex-col gap-1">
          <InputText name="password" type="password" placeholder="Пароль" fluid/>
          <Message v-if="$form.password?.invalid" severity="error" size="small" variant="simple">
            {{ $form.password.error?.message }}
          </Message>
        </div>

        <div v-if="!isLogin" class="flex flex-col gap-1">
          <InputText name="password_confirmation" type="password" placeholder="Подтвердите пароль"
                     fluid/>
          <Message v-if="$form.password_confirmation?.invalid" severity="error" size="small"
                   variant="simple">
            {{ $form.password_confirmation.error?.message }}
          </Message>
        </div>

        <Button type="submit" severity="secondary"
                :label="isLogin ? 'Войти' : 'Зарегистрироваться'"/>

        <p
          class="my-3 text-center underline-offset-4 cursor-pointer underline"
          @click="toggleMode"
        >
          {{ isLogin ? 'Нет аккаунта? Зарегистрироваться' : 'Уже есть аккаунт? Войти' }}
        </p>
      </Form>
    </div>
  </div>
</template>

<style scoped>

</style>
