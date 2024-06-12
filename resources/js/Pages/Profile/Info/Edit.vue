<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import ProfileTabs from "@/Pages/Profile/Partials/ProfileTabs.vue";
import Field from "@/Components/Field.vue";
import {useForm, usePage, Link} from "@inertiajs/vue3";
import ProfileInfoTabs from "@/Pages/Profile/Info/Tabs.vue";

export default {
    components: {Tabs: ProfileInfoTabs, Link, Field, ProfileTabs, GuestLayout},
    props: {
        mustVerifyEmail: {
            type: Boolean,
        },
        status: {
            type: String,
        },
    },
    data: () => {
        let user = usePage().props.auth.user;
        return {
            user: user,
            form: useForm({
                name: user.name,
                email: user.email,
            })
        }
    }
}


</script>

<template>
    <GuestLayout wrapper-class="profile-page">

        <div class="block">
            <ProfileTabs active="profile"/>
        </div>
        <div class="block">
            <Tabs/>
            <div class="block-bordered">
                <form @submit.prevent="form.patch(route('profile.update'))" class="form-wrapper">
                    <p v-if="status" class="status1">
                        {{ status }}
                    </p>
                    <Field :errors="form.errors" for="name" label="Ваше имя или никнейм">
                        <input class="input input-user" v-model="form.name" placeholder="Имя">
                    </Field>
                    <Field :errors="form.errors" for="email" label="Ваш e-mail">
                        <input class="input input-email" v-model="form.email" placeholder="E-mail">
                    </Field>

                    <button type="submit" class="btn btn-sm btn-default">Сохранить</button>
                </form>

                <p class="disclaimer">Нажимая "сохранить", вы соглашаетесь на 
                    <a href="/agreement" target="_blank">обработку персональных данных</a>
                </p>

                <!-- <Link href="" class="remove-profile-link">Удалить аккаунт</Link> -->

            </div>

        </div>
    </GuestLayout>
</template>

<style>
p.disclaimer {
    font-size: 11.7px;
    max-width: 380px;
    margin-top: -10px;
    margin-bottom: 15px;
}

.remove-profile-link {
    color: #C83000;
    font-size: 14px;
}

.status1 {
    font-weight: 600;
    margin: 10px 0 12px;
    font-size: 1.1em;
}
</style>
