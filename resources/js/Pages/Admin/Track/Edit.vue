<template>
    <AdminLayout :title="item.id ? item.name : 'Новый трек'"
                 :breadcrumb="[{link: route('admin.track.index'), label: 'Треки'}, item.id ? item.name: 'Новый']">


            <form method="post" @submit.prevent="submit" class="block" v-field-container>
                <h2>Основная информация</h2>
                <field :errors="form.errors" for="title" label="Название">
                    <input class="input" v-model="form.name"/>
                </field>

                <field :errors="form.errors" for="title" label="Название (EN)">
                    <input class="input" v-model="form.name_en"/>
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
                name: null,
                name_en: null,
                url: null
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
            if (this.item.id) {
                this.form.put(route('admin.track.update', {track: this.item.id}));
            } else {
                this.form.post(route('admin.track.store'));
            }
        }
    }

}
</script>
<style lang="scss" scoped>

</style>

