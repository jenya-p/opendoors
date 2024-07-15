
<template>
    <AdminLayout :title="item.id ? item.name : 'Новый тип файлов в профилях'"
                 :breadcrumb="[{link: route('admin.profile-file-type.index'), label: 'Типы файлов в профилях'}, item.id ? item.name: 'Новый тип файлов в профилях']">

        <form method="post" @submit.prevent="submit" class="block" v-field-container>
            <h2>Основная информация</h2>

            <field :errors="form.errors" for="type" label="Раздел" class="field-radios">
                <radio v-model="form.type" v-for="(name, key) of type_options" :value="key">{{name}}</radio>
            </field>

            <field :errors="form.errors" for="tracks.**" label="Треки">
                <vue-multiselect :options="track_options" v-model="form.tracks" track-by="id" label="name" :multiple="true" :closeOnSelect="false"/>
            </field>

            <field :errors="form.errors" for="name" label="Заголовок">
                <textarea-autosize v-model="form.name" class="input" :nobr="false"/>
            </field>

            <field :errors="form.errors" for="name_en" label="Заголовок (Анг.)">
                <textarea-autosize v-model="form.name_en" class="input" :nobr="false"/>
            </field>

            <field :errors="form.errors" for="summary" label="Описание">
                <ckeditor v-model="form.summary" :editor="editor" :config="{width: '100%'}"/>
            </field>

            <field :errors="form.errors" for="summary_en" label="Описание (Англ. )">
                <ckeditor v-model="form.summary_en" :editor="editor" :config="{width: '100%'}"/>
            </field>

            <table-bottom align="left">
                <button type="submit" class="btn btn-primary">Сохранить</button>
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
import CKEditor from "@ckeditor/ckeditor5-vue";
import InlineEditor from '@ckeditor/ckeditor5-build-inline';
import {selectable, selectables} from "@/Components/utils.js";
import VueMultiselect from "vue-multiselect";
import Radio from "@/Components/Radio.vue";
import TableBottom from "@/Components/TableBottom.vue";

export default {
    components: {TableBottom, Radio, TextareaAutosize, AdminLayout, Field,  ckeditor: CKEditor.component, VueMultiselect},
    props: {
        item: {
            type: Object,
            default: null
        },
        type_options: {
            type: Object, default: {}
        },
        track_options: {
            type: Array, default: []
        }
    },

    data() {
        for (const field of ['summary', 'summary_en']) {
            if (this.item[field] === null) {
                this.item[field] = '';
            }
        }
        return {
            form: useForm(this.item.id ? this.item : {
                'type': Object.keys(this.type_options)[0],
                'tracks': [],
                'name': null,
                'name_en': null,
                'summary': '',
                'summary_en': '',
            }),
            editor: InlineEditor,
        }
    },


    methods: {
        submit() {
            if (this.item.id) {
                this.form.put(route('admin.profile-file-type.update', {profile_file_type: this.item.id}));
            } else {
                this.form.post(route('admin.profile-file-type.store'));
            }
        }
    },
    // computed: {
    //   track: selectables('track_options', 'form', 'track_id'),
    // }

}
</script>


<style lang="scss" scoped>

</style>

