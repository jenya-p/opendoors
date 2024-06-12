<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import {Link, useForm} from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout wrapper-class="auth-page" title="Вход">
        <form @submit.prevent="submit" class="block">
            <h1>Вход</h1>

            <p v-if="status" class="status">
                {{ status }}
            </p>

            <div class="field" :class="{'has-error': form.errors.email}">
                <input type="text" placeholder="E-mail" class="input input-email" v-model="form.email"/>
                <input-error :message="form.errors.email"/>
            </div>

            <div class="field" :class="{'has-error': form.errors.password}">
                <input type="password" placeholder="Пароль" class="input input-password" v-model="form.password"/>
                <input-error :message="form.errors.password"/>
            </div>

            <button type="submit" class="btn btn-default">Войти</button>

            <Link
                v-if="canResetPassword"
                :href="route('password.request')"
                class="forgot-password-link"
            >Забыли пароль?
            </Link>
<!--
            <div class="hr">или</div>
            <h3>Вход через соц.сети:</h3>
            <div class="social-link-wrapper">
                <a href="javascript:;"><img src="/images/social-google.svg" alt=""></a>
                <a href="javascript:;"><img src="/images/social-vk.svg" alt=""></a>
                <a href="javascript:;"><img src="/images/social-ya.svg" alt=""></a>
            </div> -->
        </form>

        <div class="block" style="padding: 25px 35px;">
            Еще нет аккаунта? <Link :href="route('register')">Зарегистрируйтесь</Link>
        </div>
    </GuestLayout>
</template>

<style lang="scss" scoped>
h3 {
    text-align: center;
    font-weight: 500;
}
</style>
