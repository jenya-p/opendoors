<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout wrapper-class="auth-page" title="Восстановление пароля">

        <form @submit.prevent="submit" class="block">
            <h1>Восстановление пароля</h1>

            <div class="field" :class="{'has-error': form.errors.email}">
                <input type="text"
                       placeholder="E-mail"
                       class="input input-email"
                       v-model="form.email"
                       required
                       autocomplete="username"/>
                <input-error :message="form.errors.email"/>
            </div>

            <div class="field" :class="{'has-error': form.errors.password}">
                <input type="password"
                       placeholder="Пароль"
                       class="input input-password"
                       v-model="form.password"
                       required
                       autocomplete="new-password"/>
                <input-error :message="form.errors.password"/>
            </div>

            <div class="field" :class="{'has-error': form.errors.password_confirmation}">
                <input type="password"
                       placeholder="Пароль"
                       class="input input-password"
                       v-model="form.password_confirmation"
                       required
                       autocomplete="new-password"/>
                <input-error :message="form.errors.password_confirmation"/>
            </div>

            <button type="submit" class="btn btn-default">Зарегистрироваться</button>
        </form>
    </GuestLayout>
</template>
