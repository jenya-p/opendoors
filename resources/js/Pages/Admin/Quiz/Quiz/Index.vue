<template>
    <AdminLayout title="Группы заданий" :breadcrumb="[{label: 'Группы заданий'}]">

        <div class="block">
            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по профилю, треку и этапу"
                       v-model.lazy="lFilter.query">
            </div>
            <field label="Профиль">
                <div style="display:flex;">
                    <VueMultiselect :options="profile_options" v-model="profiles" :multiple="true" trackBy="id"
                                    label="name"/>
                </div>
            </field>
            <field label="Трек" class="field-checkboxes">
                <checkbox v-for="(track) of track_options" v-model="lFilter.track" :value="track.id">
                    {{ track.name }}
                </checkbox>
            </field>
            <field label="Этап" class="field-checkboxes">
                <checkbox v-for="(stage) of stage_options" v-model="lFilter.stage" :value="stage.id">{{ stage.name }}
                </checkbox>
            </field>
            <br>
            <table class="table">
                <thead class="m-hide">
                <tr>
                    <th class="profile">
                        <sort name="name" v-model="lSort">Профиль</sort>
                    </th>
                    <th class="track">
                        <sort name="track" v-model="lSort">Трек</sort>
                    </th>
                    <th class="stage">
                        <sort name="stage" v-model="lSort">Этап</sort>
                    </th>
                    <th class="question-count">
                        <sort name="question_count" v-model="lSort">Варианты</sort>
                    </th>
                    <th class="group-count">
                        <sort name="group_count" v-model="lSort">Задания</sort>
                    </th>
                    <th class="buttons"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of items" @click="itemClick(item, $event)" class="cursor-pointer" :class="{highlight: item.highlight}">
                    <td class="profile">
                        {{ item.profile.name }}
                    </td>
                    <td class="track">
                        {{ item.track_name }}
                    </td>
                    <td class="stage">
                        {{ item.stage_name }}
                    </td>
                    <td class="question-count">
                        {{ item.question_count }}
                    </td>
                    <td class="group-count">
                        {{ item.group_count }}
                    </td>
                    <td class="buttons">
                        <a class="fa fa-times btn-remove" @click.stop="remove(item)" v-if="item.can.delete"></a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";
import Ttd from "@/Components/table-td.vue";
import Sort from "@/Components/Sort.vue";
import {countNotEmpty, openEditor, paramsToUrl, selectables} from "@/Components/utils.js";
import _extend from "lodash/extend.js";
import _debounce from "lodash/debounce.js";
import VueMultiselect from "vue-multiselect";
import checkbox from "@/Components/Checkbox.vue";
import Field from "@/Components/Field.vue";
import _isArray from "lodash/isArray.js";
import IndexPage from '@/Components/index-page.js';

class Filter {

    constructor(filter = null) {
        this.set(filter);
    }

    set(defaults) {
        this.query = '';
        this.profile_id = [];
        this.track = [];
        this.stage = [];
        if (defaults) {
            _extend(this, defaults);
        }
    }

    count() {
        let count = countNotEmpty(this, ['query', 'profile_id', 'track', 'stage'])
        return count;
    }
}

export default {
    mixins: [IndexPage],
    components: {Field, Sort, Ttd, Link, AdminLayout, VueMultiselect, checkbox},
    props: {
        filter: Object,
        sort: Object,
        profile_options: Array,
        track_options: Array,
        stage_options: Array,
        items: Array
    },
    data() {
        let sort = null;
        if (this.sort != null) {
            sort = this.sort.split(':');
            if (_isArray(sort) && sort.length == 2) {
                sort = {name: sort[0], dir: sort[1]};
            } else {
                sort = null;
            }
        }

        return {
            lSort: sort,
            lFilter: new Filter(this.filter),
        };
    },
    methods: {
        itemClick: openEditor('quiz'),

        async remove(item) {
            let index = this.items.findIndex(itm => itm.id === item.id);
            let result = await axios.delete(route('admin.quiz.destroy', {quiz: item.id}));
            if (result.data.result == 'ok') {
                this.items.splice(index, 1);
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        },
        refreshPage: _debounce(function (highlightId = null) {
            var $v = this;

            let params = _extend({}, this.lFilter);
            params.sort = (this.lSort && this.lSort.name) ? (this.lSort.name + ':' + this.lSort.dir) : null;

            axios.get(route(route().current()), {
                params: params, responseType: 'json'
            }).then(function (response) {
                $v.items.length = 0;
                for (const item of response.data.items) {
                    $v.items.push(item);
                }
                $v.highlightItem(highlightId, $v.items);
                history.replaceState(null, null, route(route().current()) + '?' + paramsToUrl(params));
                $v.$forceUpdate();
            })
            .catch(function (error) {
                console.log(error);
            });


        }),
    },
    watch:{
        lFilter: {
            deep: true,
            handler() {
                this.refreshPage();
            }
        },
        lSort: {
            deep: true,
            handler() {
                this.refreshPage();
            }
        },
    },

    computed: {
        profiles: selectables('profile_options', 'lFilter', 'profile_id'),
    }

}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars.scss";

.table {
    th, td {
        &.profile {

        }
        &.track, &.stage{
            width: 15%;
        }
        &.question-count,
        &.group-count {
            width: 85px;
            text-align: center;
        }
    }
}

</style>

