
<template>
    <AdminLayout :title="item.id ? 'FAQ' : 'Новый вопрос-ответ'"
                 :breadcrumb="[
                     {link: route('admin.faq-category.index'), label: 'FAQ'},
                     item.category ? {link: route('admin.faq-category.edit', item.category.id) + '#faqs', label: item.category.name} : null,
                    item.id ? item.question: 'Новый вопрос-ответ']">

        <form method="post" @submit.prevent="submit" class="block" v-field-container>
            <h2>Основная информация</h2>
            <field :errors="form.errors" for="category_id" label="Категория">
                <vue-multiselect :options="category_options" v-model="category" track-by="id" label="name"/>
            </field>

            <field :errors="form.errors" for="title" label="Вопрос">
                <textarea-autosize v-model="form.question" class="input" :nobr="false"/>
            </field>

            <field :errors="form.errors" for="title_en" label="Вопрос (Анг.)">
                <textarea-autosize v-model="form.question_en" class="input" :nobr="false"/>
            </field>

            <field :errors="form.errors" for="answer" label="Ответ">
                <editor v-model="form.answer" :item="['faq', item.id, 'answer']"/>
            </field>

            <field :errors="form.errors" for="answer_en" label="Ответ (Англ. )">
                <editor v-model="form.answer_en" :item="['faq', item.id, 'answer']"/>
            </field>

            <div class="block-footer">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <!--                <History type="user"/>-->
            </div>

        </form>

    </AdminLayout>
</template>

<script>
import {useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import Editor from "@/Components/Editor.vue";
import {selectable} from "@/Components/utils.js";
import VueMultiselect from "vue-multiselect";

export default {
    components: {TextareaAutosize, AdminLayout, Field, Editor, VueMultiselect},
    props: {
        item: {
            type: Object,
            default: null
        },
        category_options: {
            type: Array, default: []
        }
    },

    data() {
        for (const field of ['answer', 'answer_en']) {
            if (this.item[field] === null) {
                this.item[field] = '';
            }
        }
        return {
            form: useForm(this.item.id ? this.item : {
                'category_id': null,
                'question': null,
                'question_en': null,
                'answer': '',
                'answer_en': '',
            })
        }
    },


    methods: {
        submit() {
            if (this.item.id) {
                this.form.put(route('admin.faq.update', {faq: this.item.id}));
            } else {
                this.form.post(route('admin.faq.store'));
            }
        }
    },
    computed: {
      category: selectable('category_options', 'form', 'category_id'),
    }

}
</script>


<style lang="scss" scoped>

</style>

