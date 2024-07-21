<template>
    <AdminLayout :title="item.id ? 'Задание ' + item.id : 'Новое задание'"
                 :breadcrumb="[{link: route('admin.quiz-question.index'), label: 'Задания'}, item.id ? item.id: 'Новое']">


        <div class="block" v-field-container>
            <h2>Основная информация</h2>


            <field label="Группа заданий" class="field-display">
                {{item.quiz?.name}}
            </field>

            <field label="Номер задания в тесте" class="field-display">
                {{item.group?.order}}
            </field>


            <field label="Максимальный балл за задание" class="field-display">
                {{ item.group?.weight }}
            </field>
            <field label="Направление МКН" class="field-display">
                <template v-if="item.group && item.group.theme">{{ item.group.theme.name }}</template>
                <i v-else>не указано</i>
            </field>

            <field label="Тип задания" class="field-display">
                {{item.type_name}}
            </field>


            <field label="Текст задания" class="field-display">
                <div v-html="item.text"></div>
            </field>

            <field label="Текст задания (Англ. )" class="field-display">
                <div v-html="item.text_en"></div>
            </field>
            <!--
                        <field label="Файлы">
                            <span class="attachments-lang">
                                <label>Рус</label>
                                <Attachments :items="form.images" :item_id="item.id" item_type="question" type="ru"/>
                            </span>
                            <span class="attachments-lang">
                                <label>Англ</label>
                                <Attachments :items="form.images_en" :item_id="item.id" item_type="question" type="en"/>
                            </span>
                        </field>


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


            <QuizShowMulti
                v-if="item.type == 'multi'"
                v-model:options="item.options"
                v-model:verification="item.verification"
                :max-weight="group?.weight" />
            <QuizShowNumber
                v-else-if="item.type == 'number'"
                v-model:options="item.options"
                v-model:verification="item.verification" />
            <QuizShowWords
                v-else-if="item.type == 'words'"
                v-model:options="item.options"
                v-model:verification="item.verification" />
            <QuizShowFree
                v-else-if="item.type == 'free'"
                v-model:options="item.options"
                v-model:verification="item.verification"
                :max-weight="group?.weight"
            />

            <div style="height: 70px"></div>


            <table-bottom align="left">
                <a @click="close()" class="btn btn-primary btn-editor-close fa fa-chevron-left" title="Закрыть"/>

                <!-- <a v-if="item.id" @click="submit('preview')" class="btn btn-gray"
                    style="margin-left: auto; margin-right: 0">Предпросмотр</a>
                                 <History type="user"/>-->
            </table-bottom>

        </div>

    </AdminLayout>
</template>
<script>
import {Link} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import {closeEditor} from "@/Components/utils.js";
import VueMultiselect from "vue-multiselect";
import Attachments from "@/Components/Attachments.vue";

import TableBottom from "@/Components/TableBottom.vue";
import InputLabel from "@/Components/InputLabel.vue";
import QuizShowMulti from "@/Quiz/multi/Show.vue";
import QuizShowFree from "@/Quiz/free/Show.vue";
import QuizShowNumber from "@/Quiz/number/Show.vue";
import QuizShowWords from "@/Quiz/words/Show.vue";

export default {
    components: {
        QuizShowWords,
        QuizShowNumber,
        QuizShowFree,
        QuizShowMulti,
        InputLabel,
        TableBottom,
        Attachments,
        TextareaAutosize,
        AdminLayout,
        Field,
        VueMultiselect,
        Link
    },
    props: {

        item: {
            type: Object,
            default: null
        }
    },

    data() {
        return {

        }
    },
    watch: {},



    methods: {


        close(highlight = false){
            closeEditor('quiz-question', this.item.id, highlight);
        }
    }
}
</script>

<style lang="scss" scoped src="./form.scss" />
