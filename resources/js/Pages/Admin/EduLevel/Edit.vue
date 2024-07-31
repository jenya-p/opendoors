<template>
    <AdminLayout :title="item.id ? item.name : 'Новый'"
                 :breadcrumb="[{link: route('admin.edu-level.index'), label: 'Уровни образования'}, item.id ? item.name: 'Новый']">


            <form method="post" @submit.prevent="submit" class="block" v-field-container>
                <h2>Основная информация</h2>

                <field :errors="form.errors" for="title" label="Номер п/п" class="field-short">
                    <input type="number" min="0" class="input" v-model="form.order"/>
                </field>

                <field :errors="form.errors" for="title" label="Название">
                    <input class="input" v-model="form.name"/>
                </field>

                <field :errors="form.errors" for="title" label="Название (EN)">
                    <input class="input" v-model="form.name_en"/>
                </field>

                <field :errors="form.errors" for="multiple" label="" class="field-checkboxes">
                    <checkbox v-model="form.multiple">Допускается многократное получение</checkbox>
                </field>

                <field :errors="form.errors" for="diploma" label="" class="field-checkboxes">
                    <checkbox v-model="form.diploma">Дипломная работа по завершению</checkbox>
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
import _extend from "lodash/extend";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import Checkbox from "@/Components/Checkbox.vue";

export default {
    components: {
        Checkbox,
        TextareaAutosize,
        AdminLayout,
        Field
    },
    props: {
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        if (this.item.description === null) {
            this.item.description = '';
        }
        return {
            form: useForm(_extend({
                order: null,
                name: null,
                name_en: null,
                diploma: false,
                multiple: false,
            }, this.item)),
            tabErrors: {
                info: false, questions: false
            }
        }
    },
    watch: {

    },

    methods: {
        submit() {
            this.form.errors = [];
            if (this.form.questions) {
                for (const index in this.form.questions) {
                    this.form.questions[index].order = parseInt(index) + 1;
                }
            }
            if (this.item.id) {
                this.form.put(route('admin.edu-level.update', {edu_level: this.item.id}));
            } else {
                this.form.post(route('admin.edu-level.store'));
            }
        }
    }

}
</script>
<style lang="scss" scoped>

</style>

