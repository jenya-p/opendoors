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

                <field :errors="form.errors" for="required_edu_level_ids.**" label="Необходимые уровни образования" class="field-checkboxes">
                    <checkbox v-for="eduLevel of edu_level_options" v-model="form.required_edu_level_ids" :value="eduLevel.id">{{eduLevel.name}}</checkbox>
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
        edu_level_options: Array,
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
                required_edu_level_ids: [],
            }, this.item)),
            tabErrors: {
                info: false, questions: false
            }
        }
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
.field-checkboxes:deep{
    .field-inner {
        flex-direction: column;
    }
}
</style>

