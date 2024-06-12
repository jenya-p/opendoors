<script>
import {useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import CKEditor from "@ckeditor/ckeditor5-vue";
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
    components: {TextareaAutosize, AdminLayout, Field,  ckeditor: CKEditor.component},
    props: {
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            form: useForm(this.item ? this.item : {
                title: '',
                content: '',
            }),
            editor: ClassicEditor,
        }
    },


    methods: {
        submit() {
            if (this.item) {
                this.form.put(route('admin.content.update', {content: this.item.id}));
            } else {
                this.form.post(route('admin.content.store'));
            }
        }
    }

}
</script>

<template>
    <AdminLayout :title="item ? item.title : 'Новая страница'"
                 :breadcrumb="[{link: route('admin.content.index'), label: 'Контент'}, item ? item.title: 'Новая страница']">

            <form method="post" @submit.prevent="submit" class="block" v-field-container>
                <h2>Основная информация</h2>
                <field :errors="form.errors" for="name" label="Заголовок">
                    <input class="input" v-model="form.title"/>
                </field>
                <field :errors="form.errors" for="content">
                    <ckeditor v-model="form.content" :editor="editor" :config="{
                        width: '100%'
                    }"/>
                </field>
                <div class="block-footer">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <!--                <History type="user"/>-->
                </div>

            </form>

    </AdminLayout>
</template>

<style lang="scss" scoped>

</style>

