<template>
    <AdminLayout :title="item ? ('Администратор ' + item.user.name) : 'Новый администратор'"
                 :breadcrumb="[{link: route('admin.admin.index'), label: 'Администраторы'}, item ? item.name : 'Новый администратор']">

        <div class="block-wrapper">
        <form method="post" @submit.prevent="submit" class="block" v-field-container>
            <h2>Основная информация</h2>
            <field label="Имя" class="field-display">
                <Link  v-if="item.user_id" :href="route('admin.user.edit', {user: item.user_id})">{{item.name}}</Link>
                <template v-else>{{item.name}}</template>
            </field>

            <field label="Email" class="field-display">
                <a :href="'mailto:' + item.email" target="_blank">{{item.email}}</a>
            </field>

            <field :errors="form.errors" label="Тема" class="field-display">
                {{item.subject}}
            </field>

            <field :errors="form.errors" label="Сообщение" class="field-display">
                {{ $filters.htmlize(item.body) }}
            </field>

            <field :errors="form.errors" label="Вложения" class="field-display">
                <attachments v-model="item.attachments" />
            </field>

            <h2>Комментарий менеджера</h2>

            <field :errors="form.errors" for="comment">
                <textarea-autosize class="input" v-model="form.comment"/>
            </field>

            <field :errors="form.errors" for="status" class="field-checkboxes">
                <checkbox v-model="status" >Запрос обработан</checkbox>
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
import {Head, Link, useForm} from '@inertiajs/vue3';
import Authenticated from '@/Layouts/AdminLayout.vue';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Attachments from "@/Components/Attachments.vue";


export default {
    components: {Attachments, Checkbox, TextareaAutosize, AdminLayout, Field, Authenticated, Link},
    props: {
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            form: useForm(this.item ? this.item : {
                status: '',
                comment: '',
            })
        }
    },


    methods: {
        submit() {
            if (this.item) {
                this.form.put(route('admin.backfeed.update', {backfeed: this.item.id}));
            } else {
                this.form.post(route('admin.backfeed.store'));
            }
        }
    },

    computed: {
        status: {
            get() {
                return this.form.status != 'new';
            },
            set(val){
                this.form.status = val ? 'processed' : 'new';
            }
        }
    }

}
</script>

<style lang="scss" scoped>




</style>

