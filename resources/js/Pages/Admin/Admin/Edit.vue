<template>
    <AdminLayout :title="item ? ('Администратор ' + item.user.name) : 'Новый администратор'"
                 :breadcrumb="[{link: route('admin.admin.index'), label: 'Администраторы'}, item ? item.user.name : 'Новый администратор']">

        <div class="block-wrapper">
        <form method="post" @submit.prevent="submit" class="block" v-field-container>
            <h2>Карточка администратора</h2>

            <field label="Аккаунт" class="field-display" v-if="item">
                <Link :href="route('admin.user.edit', {user: item.user.id})">{{item.user.name}} ({{item.user.email}})</Link>
            </field>
            <field label="Аккаунт" v-else :errors="form.errors" for="id">
                <UserSelect v-model="user" />
            </field>

            <field :errors="form.errors" for="roles" label="Роли" class="field-checkboxes">
                <checkbox v-model="form.roles" v-for="(name, key) of role_options" :value="key" >{{name}}</checkbox>
            </field>
            <div class="block-footer">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <!--                <History type="user"/>-->
            </div>
        </form>
        </div>
    </AdminLayout>
</template>

<script>
import {Link, useForm} from '@inertiajs/vue3';
import Authenticated from '@/Layouts/AdminLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Field from "@/Components/Field.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Attachments from "@/Components/Attachments.vue";
import UserSelect from "@/Components/UserSelect.vue";


export default {
    components: {UserSelect, Attachments, Checkbox, TextareaAutosize, AdminLayout, Field, Authenticated, Link},
    props: {
        role_options: Array,
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            form: useForm(this.item ? this.item : {
                id: null,
                roles: [],
            }),
            user: this.item ? this.item.user : null
        }
    },


    methods: {
        submit() {
            if (this.item) {
                this.form.put(route('admin.admin.update', {admin: this.item.id}));
            } else {
                this.form.post(route('admin.admin.store'));
            }
        }
    },

    watch: {
        'user'(){
            if(this.user){
                this.form.id = this.user.id
            } else {
                this.form.id = null
            }
        }
    }

}
</script>

<style lang="scss" scoped>




</style>

