<template>
    <AdminLayout :title="item.id ? 'Новость № ' + item.id : 'Добавление новости'"
                 :breadcrumb="[{link: route('admin.news.index'), label: 'Новости'}, item.id ? '№ ' + item.id: 'Новая']">

        <form method="post" @submit.prevent="submit(false)" class="block" v-field-container>
            <h2>Основная информация</h2>

            <field :errors="form.errors" for="date" label="Дата" class="field-short">
                <input type="date" v-model="form.date" class="input"/>
            </field>

            <field :errors="form.errors" for="title" label="Заголовок">
                <textarea-autosize v-model="form.title" class="input" :nobr="false"/>
            </field>

            <field :errors="form.errors" for="title_en" label="Заголовок (Анг.)">
                <textarea-autosize v-model="form.title_en" class="input" :nobr="false"/>
            </field>

            <field :errors="form.errors" for="summary" label="Краткое содержание">
                <editor v-model="form.summary" :item="['news', item.id, 'summary']"/>
            </field>

            <field :errors="form.errors" for="summary_en" label="Краткое содержание (Англ. )">
                <editor v-model="form.summary_en" :item="['news', item.id, 'summary_en']"/>
            </field>

            <field :errors="form.errors" for="content" label="Полное содержание">
                <editor v-model="form.content" :item="['news', item.id, 'content']"/>
            </field>

            <field :errors="form.errors" for="content_en" label="Полное содержание (Англ. )">
                <editor v-model="form.content_en" :item="['news', item.id, 'content_en']"/>
            </field>

            <table-bottom align="left">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </table-bottom>

        </form>

    </AdminLayout>
</template>
<script>
import {Link, useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import _extend from "lodash/extend";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import Attachments from "@/Components/Attachments.vue";
import TableBottom from "@/Components/TableBottom.vue";
import Editor from "@/Components/Editor.vue";


export default {
    components: {
        Editor,
        TableBottom,
        Attachments,
        TextareaAutosize,
        AdminLayout,
        Field,
        Link
    },
    props: {
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        for (const field of ['content', 'content_en', 'summary', 'summary_en']) {
            if (this.item[field] === null) {
                this.item[field] = '';
            }
        }

        return {
            form: useForm(_extend({
                date: null,
                title: null,
                title_en: null,
                content: '',
                content_en: '',
                summary: '',
                summary_en: '',
            }, this.item)),
        }
    },

    methods: {
        submit(preview = false) {
            if (this.item.id) {
                this.form.put(route('admin.news.update', {news: this.item.id}));
            } else {
                this.form.post(route('admin.news.store'));
            }
        }
    }

}
</script>
<style lang="scss" scoped>


</style>

