
<template>
    <AdminLayout :title="item.id ? 'Категория FAQ' : 'Новая категория FAQ'"
                 :breadcrumb="[{link: route('admin.faq-category.index'), label: 'FAQ'}, item.id ? item.name: 'Новая категория FAQ']">

        <tabs class="block">
            <tab name="main" label="Основная информация">
                <form method="post" @submit.prevent="submit" v-field-container>

                    <field :errors="form.errors" for="name" label="Название">
                        <textarea-autosize v-model="form.name" class="input" :nobr="false"/>
                    </field>

                    <field :errors="form.errors" for="name_en" label="Название (Анг.)">
                        <textarea-autosize v-model="form.name_en" class="input" :nobr="false"/>
                    </field>

                    <table-bottom align="left">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </table-bottom>
                </form>
            </tab>
            <tab name="faqs" label="Вопросы / ответы" v-if="item.id">
                <simple-list :items="item.faqs"
                             :searchable="['question']"
                             entity="faq"
                             :params="{category: item.id}">
                    <template v-slot:header="props" >
                        <th><sort name="question" v-model="props.sort" >Вопрос</sort></th>
                    </template>
                    <template v-slot:body="props" >
                        <td>{{props.item.question}}</td>
                    </template>
                </simple-list>
            </tab>
        </tabs>

    </AdminLayout>
</template>

<script>
import {useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import TableBottom from "@/Components/TableBottom.vue";
import SimpleList from "@/Components/SimpleList.vue";
import Tabs from "@/Components/tabs.vue";
import Tab from "@/Components/tab.vue";

export default {
    components: {Tab, Tabs, SimpleList, TableBottom, TextareaAutosize, AdminLayout, Field},
    props: {
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            form: useForm(this.item.id ? this.item : {
                'name': '',
                'name_en': '',
            })
        }
    },


    methods: {
        submit() {
            if (this.item.id) {
                this.form.put(route('admin.faq-category.update', {faq_category: this.item.id}));
            } else {
                this.form.post(route('admin.faq-category.store'));
            }
        }
    },

}
</script>


<style lang="scss" scoped>

</style>

