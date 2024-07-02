<template>
    <AdminLayout :title="item.id ? item.name : 'Новая группа'"
                 :breadcrumb="[{link: route('admin.quiz.index'), label: 'Группы заданий'}, item.id ? item.name: 'Новая']">


            <form method="post" @submit.prevent="submit" class="block" v-field-container>
                <h2>Основная информация</h2>

                <field class="field-display" label="Профиль">{{item.profile?.name}}</field>
                <field class="field-display" label="Трек(и)">{{item.track_name}}</field>
                <field class="field-display" label="Этап">{{item.stage_name}}</field>

                <h2>Наборы заданий и распределение баллов</h2>
                <field :errors="form.errors" for="groups">

                    <table class="table weight-table" v-if="form.groups.length">
                        <tr>
                            <th></th>
                            <th class="number">Номер</th>
                            <th class="weight" style="padding-left: 20px">Макс. балл</th>
                            <th class="count">Задания</th>
                            <th class="remove"></th>
                            <th class="errors"></th>
                        </tr>
                        <draggable
                            tag="tbody"
                            v-model="form.groups"
                            handle=".handler"
                            item-key="index"
                            ghost-class="ghost"
                            animation="200"
                        >
                            <template #item="{element, index}">
                                <tr>
                                    <td class="handler-td">
                                        <i class=" fa fa-bars handler"></i>
                                    </td>
                                    <td class="number">
                                        {{index + 1}}
                                    </td>
                                    <td class="weight">
                                        <input type="number" v-model="element.weight" class="input">
                                    </td>
                                    <td class="count">
                                        {{element.question_count}}
                                    </td>
                                    <td class="remove">
                                        <a @click="removeGroup(index)" v-if="!element.question_count" class="fa fa-times btn-remove"></a>
                                    </td>
                                    <td class="errors">
                                        <input-error :errors="form.errors" :for="'groups.' + element.index + '.**'" />
                                    </td>
                                </tr>
                            </template>
                        </draggable>

                        <tfoot>
                            <tr>
                                <th></th>
                                <th style="text-align: right">Сумма:</th>
                                <th style="padding-left: 20px">{{ sum}}</th>
                                <th>{{item.question_count}}</th>
                                <th class="remove"></th>
                                <th class="errors"></th>
                            </tr>
                        </tfoot>
                    </table>
                    <a class="btn btn-xs btn-primary" @click="addGroup" style="margin-top: 8px">Добавить</a>
                </field>

                <table-bottom align="left">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <!--                <History type="user"/>-->
                </table-bottom>


            </form>



    </AdminLayout>
</template>
<script>
import {Link, useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

import _extend from "lodash/extend";
import TableBottom from "@/Components/TableBottom.vue";
import draggable from "vuedraggable";
import _isObject from "lodash/isObject.js";
import _isArray from "lodash/isArray.js";
import InputError from "@/Components/InputError.vue";
import _isNumber from "lodash/isNumber";

let _counter = 0;

export default {
    components: {
        InputError,
        draggable,
        TableBottom,
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
        return {
            sum: this.item.groups.reduce((a, itm) => a + itm.weight, 0),
            form: useForm(_extend({
                groups: []
            }, this.item)),
        }
    },
    watch: {
        'form.groups': {
            deep:true,
            handler(){
                this.sum = this.form.groups.reduce((a, itm) => a + (_isNumber(itm.weight) ? parseFloat(itm.weight) : 0), 0)
            }
        }
    },
    methods: {
        addGroup(){
            this.form.groups.push({weight:1});
        },
        removeGroup(index){
            this.form.groups.splice(index, 1);
        },
        submit() {
            this.form.errors = [];
            _counter = 0;
            this.form.groups.map(function(itm){
                itm.index = _counter++;
            });
            if (this.item.id) {
                this.form.put(route('admin.quiz.update', {quiz: this.item.id}));
            } else {
                this.form.post(route('admin.quiz.store'));
            }
        }
    },
    created() {
        if(_isObject(this.item) && _isArray(this.item.groups)){
            this.form.groups = this.item.groups.map(function(itm){
                itm.index = _counter++;
                return itm;
            });
        } else {
            this.form.groups = [];
        }
    }

}
</script>
<style lang="scss" scoped>
@import "/resources/css/admin-vars";
.table.weight-table {

    margin: 0 0 10px 0;
    td, th {

        text-align: left;
        vertical-align: middle;
        &.remove{
            width: 50px;
            text-align: right;
        }
        &.errors{
            width: auto;
        }
        &.number{width: 100px}
        &.weight{width: 150px}
        &.count{width: 100px}
        &:first-child{
            padding-left: 15px;
            width: 30px
        }
    }

    th {

    }
    td{
        padding: 0 5px;

    }


    input {
        z-index: 100;
        border-radius: 0;
        padding: 0 5px 0 15px;
        &:not(:focus) {
            border-color: transparent;
            box-shadow: none;
        }
    }
    .handler{
        color: $dark-shadow-color;
        border-radius: 5px;
        font-size: 1.4em;
        padding: 5px;
        margin-left: -5px;
        cursor: grab;

        &:active {
            cursor: grabbing;
        }
    }
}



</style>

<style lang="scss">
.ghost {
    border-color: #ccc;
    opacity: 0.5;
}
</style>
