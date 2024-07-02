<template>
    <AdminLayout :title="item.id ? 'Задание № ' + item.id : 'Новое задание'"
                 :breadcrumb="[{link: route('admin.quiz-question.index'), label: 'Задания'}, item.id ? '№ ' + item.id: 'Новое']">

        <form method="post" @submit.prevent="submit" class="block" v-field-container>
            <h2>Основная информация</h2>

            <field :errors="form.errors" for="quiz_id" label="Группа заданий">
                <VueMultiselect :options="quiz_options" v-model="quiz" trackBy="id" label="name"
                                :allow-empty="false"/>
            </field>

            <field :errors="form.errors" for="group_id" label="Номер в билете">
                <VueMultiselect :options="group_options" v-model="group" trackBy="id" label="order"
                                :allow-empty="false"/>
            </field>

            <field :errors="form.errors" for="type" label="Тип задания">
                <VueMultiselect :options="type_options" v-model="type" trackBy="id" label="name" :allow-empty="false"/>
            </field>

            <!--
            <div class="field-row">
                <field :errors="form.errors" for="order" label="Номер задания в билете" class="field-short">
                    <input type="number" class="input" v-model="form.order" step="1" min="1">
                </field>

                <field :errors="form.errors" for="weight" label="Вес задания" class="field-short">
                    <input type="number" class="input" v-model="form.weight">
                </field>
            </div>
-->

            <field :errors="form.errors" for="text" label="Текст задания">
                <ckeditor v-model="form.text" :editor="editor" :config="{
                        width: '100%'
                    }"/>
            </field>

            <field :errors="form.errors" for="text_en" label="Текст задания (Англ. )">
                <ckeditor v-model="form.text_en" :editor="editor" :config="{
                        width: '100%'
                    }"/>
            </field>

            <field :errors="form.errors" for="images" label="Файлы">
                <Attachments :items="form.images" :item_id="item.id" item_type="question"/>
            </field>

            <field :errors="form.errors" for="images_en" label="Файлы (Англ.)">
                <Attachments :items="form.images_en" :item_id="item.id" item_type="question_en"/>
            </field>
<!--
            <field :errors="form.errors" for="description" label="Расшифровка ответа">
                <ckeditor v-model="form.description" :editor="editor" :config="{
                        width: '100%'
                    }"/>
            </field>
            <field :errors="form.errors" for="description_en" label="Расшифровка ответа (Англ.)">
                <ckeditor v-model="form.description_en" :editor="editor" :config="{
                        width: '100%'
                    }"/>
            </field>
-->    <div style="height: 30px"></div>
            <QuizEditOne
                v-if="form.type == 'one'"
                v-model:options="form.options"
                v-model:verification="form.verification"
                :errors="form.errors"/>
            <QuizEditMany
                v-else-if="form.type == 'many'"
                v-model:options="form.options"
                v-model:verification="form.verification"
                :errors="form.errors"/>
            <QuizEditMulti
                v-else-if="form.type == 'multi'"
                v-model:options="form.options"
                v-model:verification="form.verification"
                :errors="form.errors"/>
            <QuizEditNumber
                v-else-if="form.type == 'number'"
                v-model:options="form.options"
                v-model:verification="form.verification"
                :errors="form.errors"/>
            <QuizEditWords
                v-else-if="form.type == 'words'"
                v-model:options="form.options"
                v-model:verification="form.verification"
                :errors="form.errors"/>
            <QuizEditFree
                v-else-if="form.type == 'free'"
                v-model:options="form.options"
                v-model:verification="form.verification"
                :errors="form.errors"/>
            <QuizEditMatch
                v-else-if="form.type == 'match'"
                v-model:options="form.options"
                v-model:verification="form.verification"
                :errors="form.errors"/>

            <div style="height: 70px"></div>
            <!-- <component :is="typeEditor"
                v-model:options="form.options"
                         v-model:verification="form.verification"
                         :errors="form.errors"
            ></component> -->


            <table-bottom align="left">

                <button type="submit" class="btn btn-primary">Сохранить</button>

                <Link v-if="item.id" :href="route('admin.quiz-probe.probe', {question: item.id})" class="btn btn-gray" style="margin-left: auto; margin-right: 0">Предпросмотр</Link>
                <!--                <History type="user"/>-->
            </table-bottom>

        </form>

    </AdminLayout>
</template>
<script>
import {Link, useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import CKEditor from "@ckeditor/ckeditor5-vue";
import ClassicEditor from '@ckeditor/ckeditor5-build-inline';

import _extend from "lodash/extend";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import {findByIds, selectable, selectables} from "@/Components/utils.js";
import VueMultiselect from "vue-multiselect";
// import {defineAsyncComponent} from "vue";
import Attachments from "@/Components/Attachments.vue";

import QuizEditNumber from "@/Quiz/number/Edit.vue";
import QuizEditWords from "@/Quiz/words/Edit.vue";
import QuizEditOne from "@/Quiz/one/Edit.vue";
import QuizEditMany from "@/Quiz/many/Edit.vue";
import QuizEditFree from "@/Quiz/free/Edit.vue";
import QuizEditMulti from "@/Quiz/multi/Edit.vue";
import QuizEditMatch from "@/Quiz/match/Edit.vue";
import TableBottom from "@/Components/TableBottom.vue";
import InputLabel from "@/Components/InputLabel.vue";

export default {
    components: {
        InputLabel,
        TableBottom,
        QuizEditMulti,
        QuizEditOne,
        QuizEditWords,
        QuizEditNumber,
        QuizEditMany,
        QuizEditFree,
        QuizEditMatch,
        Attachments,
        TextareaAutosize,
        AdminLayout,
        Field,
        ckeditor: CKEditor.component,
        VueMultiselect,
        Link
    },
    props: {
        type_options: {
            type: Array,
            default: null
        },
        quiz_options: {
            type: Array,
            default: null
        },
        group_options: {
            type: Array,
            default: null
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
        if (this.item.description_en === null) {
            this.item.description_en = '';
        }
        return {
            form: useForm(_extend({
                quiz_id: null,
                group_id: null,
                text: '',
                text_en: '',
                type: 'one',
                options: [],
                verification: [],
                images: [],
                images_en: []

            }, this.item)),
            editor: ClassicEditor,
        }
    },
    watch: {},

    computed: {
        type: selectable('type_options', 'form', 'type'),
        group: selectable('group_options', 'form', 'group_id'),
        quiz: selectable('quiz_options', 'form', 'quiz_id'),
        // typeEditor () {
        //     if(this.type && this.type.id){
        //
        //
        //         // TODO Поправить так: https://laracasts.com/discuss/channels/inertia/inertia-test-fails-unable-to-locate-file-in-vite-manifest
        //
        //
        //         return defineAsyncComponent(() => import(`../../../Quiz/${this.type.id}/Edit.vue`))
        //     }else {
        //         return null;
        //     }
        //
        // }
    },

    methods: {
        submit() {
            if (this.item.id) {
                this.form.put(route('admin.quiz-question.update', {question: this.item.id}));
            } else {
                this.form.post(route('admin.quiz-question.store'));
            }
        }
    }

}
</script>
<style lang="scss" scoped>


:deep(form){
    &:not(.vertical) .field-row{
        display: flex;
        gap: 40px;
        .input{
            width: 190px;
        }
        .field{margin: 0}
        .field:not(:first-child) .input-label{text-align: right; width: auto;}
    }
    &.vertical .field-row .field{
        flex-direction: column;
        align-items: stretch;
    }
}



</style>

