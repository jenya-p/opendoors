<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout wrapper-class="auth-page" title="Подтверждение пароля">

        <form @submit.prevent="submit" class="block">
            <h1>Подтверждение пароля</h1>

            <p>
                Это защищенная область приложения. Пожалуйста, подтвердите свой пароль, прежде чем продолжить.
            </p>

            <div class="field" :class="{'has-error': form.errors.password}">
                <input type="password" placeholder="Пароль" class="input input-password" v-model="form.password"/>
                <input-error :message="form.errors.password"/>
            </div>

            <button type="submit" class="btn btn-default"
                    :disabled="form.processing">Подтвердить</button>
        </form>
        <div class="block" style="padding: 25px 35px;">
            Еще нет аккаунта? <Link :href="route('register')">Зарегистрируйтесь</Link>
        </div>
    </GuestLayout>
</template>
