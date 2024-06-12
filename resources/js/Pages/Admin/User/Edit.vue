<template>
    <AdminLayout :title="item ? item.name : 'Новый пользователь'"
                 :breadcrumb="[{link: route('admin.user.index'), label: 'Пользователи'}, item ? item.display_name : 'Новый пользователь']">

        <div class="block-wrapper">
            <form method="post" @submit.prevent="submit" class="block" v-field-container>
                <h2>Основная информация</h2>
                <field :errors="form.errors" for="name" label="Имя">
                    <input class="input" v-model="form.name"/>
                </field>


                <field :errors="form.errors" for="email" label="Email">
                    <input class="input" v-model="form.email"/>
                </field>

                <template v-if="!item">
                    <field :errors="form.errors" for="password" label="Пароль">
                        <input type="password" class="input" v-model="form.password"/>
                    </field>

                    <field :errors="form.errors" for="password_confirmation"
                           label="Подтверждение" description="Повторите ввод пароля">
                        <input type="password" class="input" v-model="form.password_confirmation"/>
                    </field>
                </template>

                <div class="block-footer">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <!--                <History type="user"/>-->
                </div>

            </form>


            <password v-if="item && item.id == $page.props.auth.user.id" :item="item"/>

        </div>


    </AdminLayout>
</template>

<script>
import {Head, Link, useForm} from '@inertiajs/vue3';
import Authenticated from '@/Layouts/AdminLayout.vue';
import Field from "@/Components/Field.vue";
import Password from "@/Pages/Admin/User/Password.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    components: {AdminLayout, Password, Field, Authenticated},
    props: {
        admin_roles: Object,
        university_roles: Object,
        item: {
            type: Object,
            default: null
        },
        role_options: {
            type: Array,
        },
    },

    data() {
        return {
            form: useForm(this.item ? this.item : {
                name: '',
                email: '',
                roles: [],
                password: '',
                password_confirmation: '',
            })
        }
    },


    methods: {
        submit() {
            if (this.item) {
                this.form.put(route('admin.user.update', {user: this.item.id}));
            } else {
                this.form.post(route('admin.user.store'));
            }
        }
    }

}
</script>


<style lang="scss" scoped>

@media screen and (min-width: 1600px) {
    .block-wrapper {
        gap: 50px;
        display: flex;
        width: 100%;
        align-items: flex-start;

        & > .block {
            width: 50%;
            flex-grow: 1;
            margin-bottom: 0;
        }
    }
}

@media screen and (max-width: 1600px) {
    .block-wrapper {
        display: flex;
        flex-direction: column;
        gap: 40px;

        & > .block {
        }
    }
}
</style>

