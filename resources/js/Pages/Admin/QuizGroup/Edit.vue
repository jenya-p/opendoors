<template>
    <AdminLayout :title="item.id ? item.name : 'Новая группа'"
                 :breadcrumb="[{link: route('admin.quiz-group.index'), label: 'Группы вопросов'}, item.id ? item.name: 'Новая']">


            <form method="post" @submit.prevent="submit" class="block" v-field-container>
                <h2>Основная информация</h2>
                <field :errors="form.errors" for="name" label="Название">
                    <input class="input" v-model="form.name"/>
                </field>

                <div class="block-footer">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <!--                <History type="user"/>-->
                </div>

            </form>



    </AdminLayout>
</template>
<script>
import {Link, useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

import _isObject from "lodash/isObject";
import _extend from "lodash/extend";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";

export default {
    components: {
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
        return {
            form: useForm(_extend({
                name: null,
                url: null
            }, this.item)),
        }
    },
    watch: {

    },

    methods: {
        submit() {
            this.form.errors = [];
            if (this.item.id) {
                this.form.put(route('admin.quiz-group.update', {quiz_group: this.item.id}));
            } else {
                this.form.post(route('admin.quiz-group.store'));
            }
        }
    }

}
</script>
<style lang="scss" scoped>

</style>

