<template>
    <AdminLayout :title="item ? item.title : 'Новая запись графика'"
                 :breadcrumb="[{link: route('admin.schedule.index'), label: 'График'}, item.id ? item.title: 'Новая запись']">

        <form method="post" @submit.prevent="submit" class="block" v-field-container>
            <h2>Основная информация</h2>

            <field :errors="form.errors" for="date_from" label="Дата начала" class="field-short">
                <input type="date" v-model="form.date_from" class="input"/>
            </field>

            <field :errors="form.errors" for="date_to" label="Дата завершения" class="field-short">
                <input type="date" v-model="form.date_to" class="input"/>
            </field>


            <field :errors="form.errors" for="title" label="Заголовок">
                <textarea-autosize v-model="form.title" class="input" :nobr="false"/>
            </field>

            <field :errors="form.errors" for="title_en" label="Заголовок (Анг.)">
                <textarea-autosize v-model="form.title_en" class="input" :nobr="false"/>
            </field>

            <field :errors="form.errors" for="summary" label="Краткое содержание">
                <editor v-model="form.summary" :item="['question', item.id, 'summary']"/>
            </field>

            <field :errors="form.errors" for="summary_en" label="Краткое содержание (Англ. )">
                <editor v-model="form.summary_en" :item="['question', item.id, 'summary_en']"/>
            </field>

            <field :errors="form.errors" for="details" label="Подробное расписание">
                <editor v-model="form.details" :item="['question', item.id, 'details']"/>
            </field>

            <field :errors="form.errors" for="details_en" label="Подробное расписание (Англ. )">
                <editor v-model="form.details_en" :item="['question', item.id, 'details_en']"/>
            </field>

            <table-bottom align="left">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </table-bottom>

        </form>

    </AdminLayout>
</template>

<script>
import {useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import Editor from "@/Components/Editor.vue";
import TableBottom from "@/Components/TableBottom.vue";

export default {
    components: {TableBottom, TextareaAutosize, AdminLayout, Field, Editor},
    props: {
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            form: useForm(this.item.id ? this.item : {
                date_from: null,
                date_to: null,
                title: null,
                title_en: null,
                content: '',
                content_en: '',
                details: '',
                details_en: '',
            }),
        }
    },


    methods: {
        submit() {
            if (this.item.id) {
                this.form.put(route('admin.schedule.update', {schedule: this.item.id}));
            } else {
                this.form.post(route('admin.schedule.store'));
            }
        }
    }

}
</script>


<style lang="scss" scoped>

</style>

