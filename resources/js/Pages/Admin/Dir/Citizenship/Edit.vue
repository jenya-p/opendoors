<template>
    <AdminLayout :title="item.id ? item.name : 'Новое'"
                 :breadcrumb="[{link: route('admin.dir-citizenship.index'), label: 'Гражданство'}, item.id ? item.name: 'Новый']">


            <form method="post" @submit.prevent="submit" class="block" v-field-container>
                <h2>Основная информация</h2>
                <field :errors="form.errors" for="name" label="Название">
                    <input class="input" v-model="form.name"/>
                </field>

                <field :errors="form.errors" for="name_en" label="Название (EN)">
                    <input class="input" v-model="form.name_en"/>
                </field>

                <field :errors="form.errors" for="code" label="Код">
                    <input class="input" v-model="form.code"/>
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
                code: null,
            }, this.item))
        }
    },
    watch: {

    },

    methods: {
        submit() {
            this.form.errors = [];
            if (this.item.id) {
                this.form.put(route('admin.dir-citizenship.update', {citizenship: this.item.id}));
            } else {
                this.form.post(route('admin.dir-citizenship.store'));
            }
        }
    }

}
</script>
<style lang="scss" scoped>
@import "resources/css/admin-vars.scss";

</style>

