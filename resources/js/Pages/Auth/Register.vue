<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import Checkbox from '@/Components/Checkbox.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import field from "@/Components/Field.vue";

const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    confirm: false
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout wrapper-class="auth-page" title="Регистрация">

        <form @submit.prevent="submit" class="block">

            <h1>Регистрация</h1>



            <field :errors="form.errors" for="email">
                <input type="text"
                       placeholder="E-mail"
                       class="input input-email"
                       v-model="form.email"
                       required
                       autocomplete="username"/>
            </field>

            <field :errors="form.errors" for="name">
                <input type="text"
                          placeholder="Имя"
                          class="input input-name"
                          v-model="form.name">
            </field>

            <field :errors="form.errors" for="password">
                <input type="password"
                       placeholder="Пароль"
                       class="input input-password"
                       v-model="form.password"
                       required
                       autocomplete="new-password"/>
            </field>

            <field :errors="form.errors" for="password_confirmation">
                <input type="password"
                       placeholder="Пароль"
                       class="input input-password"
                       v-model="form.password_confirmation"
                       required
                       autocomplete="new-password"/>
            </field>

            <div class="field" :class="{'has-error': form.errors.confirm}">
                <Checkbox v-model="form.confirm" class="confirm-checkbox">Я принимаю условия <a href="/agreement" target="_blank">пользовательского соглашения</a></Checkbox>
                <input-error :message="form.errors.confirm"/>
            </div>

            <button type="submit" class="btn btn-default">Зарегистрироваться</button>

        </form>
        <div class="block">
            Уже зарегистрированы? <Link :href="route('login')">Войдите</Link>
        </div>
    </GuestLayout>
</template>

<style>
.confirm-checkbox{
    display: flex;
    align-items: center;
}
</style>
