<template>
    <AdminLayout :title="item.id ? item.name : 'Новая группа'"
                 :breadcrumb="[{link: route('admin.quiz.index'), label: 'Группы заданий'}, item.id ? item.name: 'Новая']">


            <form method="post" @submit.prevent="submit(false)" class="block" v-field-container>
                <tabs>
                    <tab label="Основная информация" name="main">

                        <field class="field-display" label="Профиль">{{item.profile?.name}}</field>
                        <field class="field-display" label="Трек(и)">{{item.track_name}}</field>
                        <field class="field-display" label="Этап">{{item.stage_name}}</field>
                        <h2>Задания и распределение баллов</h2>
                        <field :errors="form.errors" for="groups">
                            <table class="table weight-table" v-if="form.groups.length">
                                <tr>
                                    <th></th>
                                    <th class="number">Номер задания в тесте</th>
                                    <th class="weight" style="padding-left: 20px">Макс. балл</th>
                                    <th class="count">Варианты</th>
                                    <th class="theme">Направление МКН</th>
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
                                            <td class="theme">
                                                <theme-input v-model:options="lThemeOptions" v-model:value="element.theme" />
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

                    </tab>
                    <tab label="Пользователи" name="roles" v-if="item.can.manage">
                        <user-roles :roles="role_options" v-model:items="form.roles"/>
                    </tab>
                </tabs>


                <table-bottom align="left">
                    <a @click="close()" class="btn btn-primary btn-editor-close fa fa-chevron-left" title="Закрыть"/>
                    <button type="submit" :disabled="!form.isDirty"  class="btn btn-primary">Сохранить</button>
                    <button type="button" :disabled="!form.isDirty"  @click="submit(true)" class="btn btn-primary">Сохранить и закрыть</button>

                    <!--                <History type="user"/>-->
                </table-bottom>

            </form>



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
        UserRoles,
        ThemeInput,
        VueMultiselect,
        InputError,
        draggable,
        TableBottom,
        AdminLayout,
        Field,tabs,tab
    },
    props: {
        item: {
            type: Object,
            default: null
        },
        theme_options: {
            type: Array,
            default: []
        },
        role_options: {
            type: Array,
            default: []
        }
    },

    data() {

        let $v = this;
        let groups = [];
        if(_isObject(this.item) && _isArray(this.item.groups)){
            groups = this.item.groups.map(function(group){
                if(group.theme_id){
                    group.theme = $v.theme_options.find(theme => theme.id == group.theme_id);
                }
                group.index = _counter++;
                return group;
            });
        }

        return {
            lThemeOptions: this.theme_options,
            sum: this.item.groups.reduce((a, itm) => a + itm.weight, 0),
            form: useForm(_extend({
                groups: groups,
                roles: []
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
            this.form.groups.push({weight:1, theme: null});
        },
        removeGroup(index){
            this.form.groups.splice(index, 1);
        },
        submit(close = false) {
            this.form.errors = [];
            let $v = this;
            _counter = 0;
            this.form.groups.map(function(itm){
                itm.index = _counter++;
            });

            let inertiaOpts = {
                preserveScroll: true
            };

            if (close) {
                inertiaOpts.onSuccess = function(){
                    $v.close(true);
                };
            }

            if (this.item.id) {
                this.form.put(route('admin.quiz.update', {quiz: this.item.id}), inertiaOpts);
            } else {
                this.form.post(route('admin.quiz.store'), inertiaOpts);
            }
        },
        close(highlight = false){
            closeEditor('quiz', this.item.id, highlight);
        }
    },
    created() {

    }

}
</script>
<style lang="scss" scoped src="./form.scss" />


<style lang="scss">
.ghost {
    border-color: #ccc;
    opacity: 0.5;
}
</style>
