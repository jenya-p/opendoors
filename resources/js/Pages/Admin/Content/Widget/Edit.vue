<template>
    <AdminLayout :title="item ? item.name : 'Новая страница'"
                 :breadcrumb="[{link: route('admin.widget.index'), label: 'Виджеты'}, item ? item.name: 'Новая страница']">

        <form method="post" @submit.prevent="submit" class="block" v-field-container>
            <h2>Основная информация</h2>
            <field :errors="form.errors" for="data">
                <json-input v-model="form.content" :schema="schema" v-if="schema"></json-input>
                <template v-else>
                    <p class="input-error">Не задана схема виджета, доступно только редактирование JSON</p>
                    <textarea-autosize v-model.lazy="jsonContent" class="input"/>
                </template>
            </field>
            <table-bottom align="left">
                <button type="submit" class="btn btn-primary" :disabled="!form.isDirty">Сохранить</button>
                <!--                <History type="user"/>-->
            </table-bottom>
        </form>

    </AdminLayout>
</template>

<script>
import {useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import JsonInput from "@/Components/JsonInput.vue";
import TableBottom from "@/Components/TableBottom.vue";
import Attachments from "@/Components/Attachments.vue";

export default {
    components: {Attachments, TableBottom, JsonInput, TextareaAutosize, AdminLayout, Field},
    props: {
        item: {
            type: Object,
            default: null
        },
        schema: Object,

    },

    data() {
        return {
            form: useForm(this.item ? {
                content: this.item.data,
                attachments: this.item.attachments
            } : {
                content: {},
                attachments: {},
            }),
        }
    },

    computed: {
        jsonContent: {
            get(){
                return JSON.stringify(this.form.content, null, 2);
            }
        }
    },

    methods: {
        submit() {
            if (this.item) {
                this.form.put(route('admin.widget.update', {widget: this.item.id}));
            } else {
                this.form.post(route('admin.widget.store'));
            }
        }
    }

}
</script>


<style lang="scss" scoped>

</style>

