<template>
    <AdminLayout :title="item.id ? item.name : 'Новый университет'"
                 :breadcrumb="[{link: route('admin.university.index'), label: 'Университеты'}, item.id ? item.name: 'Новый университет']">


            <form method="post" @submit.prevent="submit" class="block" v-field-container>
                <h2>Основная информация</h2>
                <field :errors="form.errors" for="name" label="Название">
                    <textarea-autosize class="input" v-model="form.name"/>
                </field>

                <field :errors="form.errors" for="name_en" label="Название (EN)">
                    <textarea-autosize class="input" v-model="form.name_en"/>
                </field>

                <field :errors="form.errors" for="url" label="Ссылка">
                    <input class="input" v-model="form.url"/>
                </field>

                <field :errors="form.errors" for="url_en" label="Ссылка (En)">
                    <input class="input" v-model="form.url_en"/>
                </field>

                <field :errors="form.errors" for="logo" label="Лого">
                    <div style="display: flex; gap: 15px">
                        <attachment v-model:item="form.logo" item_type="university" :item_id="item.id" :single="true" title="Рус" type="logo_ru"/>
                        <attachment v-model:item="form.logo_en" item_type="university" :item_id="item.id" :single="true" title="Англ" type="logo_en"/>
                    </div>
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
import Attachment from "@/Components/Attachment.vue";

export default {
    components: {
        Attachment,
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
                url: null,
                url_en: null,
                logo: null,logo_en: null,
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
                this.form.put(route('admin.university.update', {university: this.item.id}));
            } else {
                this.form.post(route('admin.university.store'));
            }
        }
    }

}
</script>
<style lang="scss" scoped>

</style>

