<template>
    <AdminLayout :breadcrumb="[
        {link: route('lk.dashboard'), label: $t('Личный кабинет')},
        {link: route('lk.achievement.index'), label: $t('Достижения')},
        item.id ? item.type.name : $t('Новое достижение')
        ]" :title="$t('Достижения')">
        <form method="post" @submit.prevent="submit" class="block lk-form" v-field-container>

            <field :errors="form.errors" for="type_id" label="Тип достижения">
                <vue-multiselect :options="type_options" v-model="type" track-by="id" label="name"/>
            </field>
            <json-input v-if="type" :schema="type.schema" v-model="form.content" :attachment-ids="form.attachment_ids"/>

            <table-bottom align="left">
                <a @click="close()" class="btn btn-primary btn-editor-close fa fa-chevron-left" title="Закрыть"/>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </table-bottom>

        </form>
    </AdminLayout>
</template>
<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TableBottom from "@/Components/TableBottom.vue";
import {router, useForm} from "@inertiajs/vue3";
import {selectable} from "@/Components/utils.js";
import Field from "@/Components/Field.vue";
import VueMultiselect from "vue-multiselect";
import Checkbox from "@/Components/Checkbox.vue";
import Attachments from "@/Components/Attachments.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import InputError from "@/Components/InputError.vue";
import _extend from "lodash/extend";
import JsonInput from "@/Components/JsonInput.vue";

export default {
    components: {
        JsonInput,
        InputError, TextareaAutosize, Attachments, Checkbox, Field, TableBottom, AdminLayout, VueMultiselect},
    props: {
        type_options: Array,
        item: {
            type: Object,
            default: null
        }
    },

    data() {

        return {
            form: useForm(_extend({
                type_id: null,
                content: {},
                attachment_ids: []
            }, this.item))
        }
    },

    methods: {
        submit() {
            if (this.item.id) {
                this.form.put(route('lk.achievement.update', {achievement: this.item.id}));
            } else {
                this.form.post(route('lk.achievement.store'));
            }
        },
        close(highlight = false){
            router.visit(route('lk.achievement.index'));
        }
    },

    computed:{
        type: selectable('type_options', 'form', 'type_id'),
    }


}

</script>

<i18n src="@/Pages/Lk/translations.json" />
<i18n>
{
    "ru": {


    },
    "en": {


    }
}
</i18n>
