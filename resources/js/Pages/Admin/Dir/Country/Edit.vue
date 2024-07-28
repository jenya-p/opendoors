<template>
    <AdminLayout :title="item.id ? item.name : 'Новая страна'"
                 :breadcrumb="[{link: route('admin.dir-country.index'), label: 'Страны'}, item.id ? item.name: 'Новый']">


            <form method="post" @submit.prevent="submit" class="block" v-field-container>
                <h2>Основная информация</h2>
                <field :errors="form.errors" for="title" label="Название">
                    <input class="input" v-model="form.name"/>
                </field>

                <field :errors="form.errors" for="title" label="Название (EN)">
                    <input class="input" v-model="form.name_en"/>
                </field>

                <field :errors="form.errors" for="code" label="Код">
                    <input class="input" v-model="form.code"/>
                </field>

                <field :errors="form.errors" for="region_id" label="Регион">
                    <VueMultiselect
                        label="name"
                        track-by="id"
                        :options="region_options" v-model="region"/>
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
import {selectable} from "@/Components/utils.js";
import VueMultiselect from "vue-multiselect";
export default {
    components: {
        TextareaAutosize,
        AdminLayout,
        Field,
        VueMultiselect
    },
    props: {
        region_options: {
            type: Array,
            default: []
        },
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
                region_id: null
            }, this.item))
        }
    },
    computed: {
        region: selectable('region_options', 'form', 'region_id')
    },

    methods: {
        submit() {
            this.form.errors = [];
            if (this.item.id) {
                this.form.put(route('admin.dir-country.update', {country: this.item.id}));
            } else {
                this.form.post(route('admin.dir-country.store'));
            }
        }
    }

}
</script>
<style lang="scss" scoped>
@import "resources/css/admin-vars.scss";

</style>

