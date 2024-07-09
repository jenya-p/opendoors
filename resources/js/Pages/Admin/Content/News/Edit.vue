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
                <ckeditor v-model="form.summary" :editor="editor" :config="{width: '100%', 'max-height': 500}"/>
            </field>

            <field :errors="form.errors" for="summary_en" label="Краткое содержание (Англ. )">
                <ckeditor v-model="form.summary_en" :editor="editor" :config="{width: '100%'}"/>
            </field>

            <field :errors="form.errors" for="content" label="Краткое содержание">
                <ckeditor v-model="form.content" :editor="editor" :config="{width: '100%'}"/>
            </field>

            <field :errors="form.errors" for="content_en" label="Краткое содержание (Англ. )">
                <ckeditor v-model="form.content_en" :editor="editor" :config="{width: '100%'}"/>
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
import CKEditor from "@ckeditor/ckeditor5-vue";
import ClassicEditor from '@ckeditor/ckeditor5-build-inline';

import _extend from "lodash/extend";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import Attachments from "@/Components/Attachments.vue";
import TableBottom from "@/Components/TableBottom.vue";


export default {
    components: {
        TableBottom,
        Attachments,
        TextareaAutosize,
        AdminLayout,
        Field,
        ckeditor: CKEditor.component,
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
            editor: ClassicEditor,
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

