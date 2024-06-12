<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout wrapper-class="auth-page" title="Восстановление пароля">
        <form @submit.prevent="submit" class="block">
            <h1>Восстановление пароля</h1>

            <p>
                Забыли свой пароль? Без проблем. Просто сообщите нам свой адрес электронной почты, и мы вышлем вам по электронной почте сообщение о сбросе пароля
                ссылка, которая позволит вам выбрать новый.
            </p>

            <p v-if="status" class="status">
                {{ status }}
            </p>

            <div class="field" :class="{'has-error': form.errors.email}">
                <input type="text" placeholder="E-mail" class="input input-email" v-model="form.email"/>
                <input-error :message="form.errors.email"/>
            </div>

            <button type="submit" class="btn btn-default"
                    :disabled="form.processing">Востановить пароль</button>
        </form>
    </GuestLayout>
</template>
