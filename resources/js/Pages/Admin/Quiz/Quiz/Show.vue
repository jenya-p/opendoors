<template>
    <AdminLayout :title="item.id ? item.name : 'Новая группа'"
                 :breadcrumb="[{link: route('admin.quiz.index'), label: 'Группы заданий'}, item.id ? item.name: 'Новая']">


        <div class="block" v-field-container>
            <h2>Основная информация</h2>


            <field class="field-display" label="Профиль">{{ item.profile?.name }}</field>
            <field class="field-display" label="Трек(и)">{{ item.track_name }}</field>
            <field class="field-display" label="Этап">{{ item.stage_name }}</field>
            <h2>Задания и распределение баллов</h2>
            <field for="groups">
                <table class="table weight-table" v-if="item.groups.length">
                    <tr>
                        <th class="number">Номер задания в тесте</th>
                        <th class="weight" style="padding-left: 20px">Макс. балл</th>
                        <th class="count">Варианты</th>
                        <th class="theme">Направление МКН</th>
                    </tr>
                    <tr v-for="(element, index) of item.groups">

                        <td class="number">
                            {{ index + 1 }}
                        </td>
                        <td class="weight">
                            {{ element.weight }}
                        </td>
                        <td class="count">
                            {{ element.question_count }}
                        </td>
                        <td class="theme">
                            {{ element.theme?.name }}
                        </td>
                    </tr>

                    <tfoot>
                    <tr>
                        <th></th>
                        <th style="text-align: right">Сумма:</th>
                        <th style="padding-left: 20px">{{ sum }}</th>
                        <th>{{ item.question_count }}</th>
                        <th class="remove"></th>
                        <th class="errors"></th>
                    </tr>
                    </tfoot>
                </table>
            </field>

            <table-bottom align="left">
                <a @click="close()" class="btn btn-primary btn-editor-close fa fa-chevron-left" title=""/>
            </table-bottom>

        </div>


    </AdminLayout>
</template>
<script>
import {Link, router, useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

import _extend from "lodash/extend";
import TableBottom from "@/Components/TableBottom.vue";
import draggable from "vuedraggable";
import _isObject from "lodash/isObject.js";
import _isArray from "lodash/isArray.js";
import InputError from "@/Components/InputError.vue";
import _isNumber from "lodash/isNumber";
import VueMultiselect from "vue-multiselect";
import ThemeInput from "@/Pages/Admin/Quiz/Quiz/ThemeInput.vue";
import {closeEditor} from "@/Components/utils.js";
import UserRoles from "@/Components/UserRoles.vue";
import tabs from "@/Components/tabs.vue";
import tab from "@/Components/tab.vue";

let _counter = 0;

export default {
    components: {
        TableBottom,
        AdminLayout,
        Field, tabs, tab
    },
    props: {
        item: {
            type: Object,
            default: null
        },
    },

    data() {

        return {
            sum: this.item.groups.reduce((a, itm) => a + itm.weight, 0),
        }
    },
    methods: {
        close(highlight = false) {
            closeEditor('quiz', this.item.id, highlight);
        }
    }

}
</script>
<style lang="scss" scoped src="./form.scss" />



