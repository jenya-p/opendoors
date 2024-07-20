<template>
    <AdminLayout :title="item.id ? 'Задание ' + item.id : 'Новое задание'"
                 :breadcrumb="[{link: route('admin.quiz-question.index'), label: 'Задания'}, item.id ? item.id: 'Новое']">

        <form method="post" @submit.prevent="submit(null)" class="block" v-field-container>
            <h2>Основная информация</h2>


            <field :errors="form.errors" for="quiz_id" label="Группа заданий">
                <VueMultiselect :options="quiz_options" v-model="quiz" trackBy="id" label="name"
                                :allow-empty="false"/>
            </field>

            <!-- <field :errors="form.errors" for="status" label="Статус" class="field-short">
                <VueMultiselect :options="status_options" v-model="status" trackBy="id" label="name"
                                :allow-empty="false"/>
            </field> -->

            <div class="field-row" v-if="quiz">
                <field :errors="form.errors" for="order" label="Номер задания в тесте" class="field-short">
                    <VueMultiselect :options="group_options" v-model="group" trackBy="id" label="order"
                                    :allow-empty="false"/>
                </field>
            </div>

            <div v-if="group">
                <field :errors="form.errors" for="weight" label="Максимальный балл за задание" class="field-display">
                    {{ group.weight }}
                </field>
                <field label="Направление МКН" class="field-display">
                    <template v-if="group && group.theme">{{ group.theme.name }}</template>
                    <i v-else>не указана</i>
                </field>
            </div>

            <field :errors="form.errors" for="type" label="Тип задания">
                <VueMultiselect :options="type_options" v-model="type" trackBy="id" label="name" :allow-empty="false"/>
            </field>


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

            <field :errors="form.errors" for="images, images_en" label="Файлы">
                <span class="attachments-lang">
                    <label>Рус</label>
                    <Attachments :items="form.images" :item_id="item.id" item_type="question" type="ru"/>
                </span>
                <span class="attachments-lang">
                    <label>Англ</label>
                    <Attachments :items="form.images_en" :item_id="item.id" item_type="question" type="en"/>
                </span>
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
            -->
            <div style="height: 30px"></div>
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
                :errors="form.errors"
                :max-weight="group?.weight"
            />
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
                :errors="form.errors"
                :max-weight="group?.weight"
            />
            <QuizEditMatch
                v-else-if="form.type == 'match'"
                v-model:options="form.options"
                v-model:verification="form.verification"
                :errors="form.errors"
                :max-weight="group?.weight"
            />

            <div style="height: 70px"></div>
            <!-- <component :is="typeEditor"
                v-model:options="form.options"
                         v-model:verification="form.verification"
                         :errors="form.errors"
            ></component> -->


            <table-bottom align="left">
                <a @click="close()" class="btn btn-primary btn-editor-close fa fa-chevron-left" title="Закрыть"/>
                <button type="submit" :disabled="!form.isDirty" class="btn btn-primary">Сохранить</button>
                <button type="button" :disabled="!form.isDirty" @click="submit('close')" class="btn btn-primary">
                    Сохранить и закрыть
                </button>

                <a v-if="item.id" @click="submit('preview')" class="btn btn-gray"
                   style="margin-left: auto; margin-right: 0">Предпросмотр</a>
                <!--                <History type="user"/>-->
            </table-bottom>

        </form>

    </AdminLayout>
</template>
<script>
import {Link, router, useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import CKEditor from "@ckeditor/ckeditor5-vue";
import ClassicEditor from '@ckeditor/ckeditor5-build-inline';

import _extend from "lodash/extend";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import {closeEditor, findByIds, selectable, selectables} from "@/Components/utils.js";
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
        status_options: {
            type: Array,
            default: null
        },
        quiz_options: {
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
                images_en: [],
                status: 'active'
            }, this.item)),
            editor: ClassicEditor,
        }
    },
    watch: {},

    computed: {
        group_options() {
            if (this.quiz) {
                return this.quiz.groups;
            } else {
                return []
            }
        },
        status: selectable('status_options', 'form', 'status'),
        type: selectable('type_options', 'form', 'type'),
        quiz: selectable('quiz_options', 'form', 'quiz_id'),
        group: selectable('group_options', 'form', 'group_id'),

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

        submit(mode = null) {
            this.form.errors = [];
            let $v = this;

            let inertiaOpts = {
                preserveScroll: true
            };

            if (mode == 'close') {
                inertiaOpts.onSuccess = function(){
                    $v.close(true);
                };
            }

            if (this.item.id) {
                this.form.put(route('admin.quiz-question.update', {
                    question: this.item.id,
                    preview: mode == 'preview'
                }), inertiaOpts);
            } else {
                this.form.post(route('admin.quiz-question.store', {preview: mode == 'preview'}), inertiaOpts);
            }
        },

        close(highlight = false){
            closeEditor('quiz-question', this.item.id, highlight);
        }
    }
}
</script>
<style lang="scss" scoped>
@import "resources/css/admin-vars";

:deep(form) {
    &:not(.vertical) .field-row {
        display: flex;
        gap: 40px;

        .input {
            width: 190px;
        }

        .field {
            margin: 0
        }

        .field:not(:first-child) .input-label {
            text-align: right;
            width: auto;
        }

        .field-short.field-display {
            & > div, & > label {
                line-height: 43px;
            }
        }
    }

    &.vertical .field-row .field {
        flex-direction: column;
        align-items: stretch;
    }

    .attachments-lang {
        display: inline-flex;
        align-content: center;
        flex-direction: column;

        margin-right: 30px;
        margin-bottom: 20px;

        & > label {
            font-weight: 500;
            font-size: 0.9em;
            width: 100%;
            border-bottom: 1px solid $shadow-color;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
    }
}


</style>

