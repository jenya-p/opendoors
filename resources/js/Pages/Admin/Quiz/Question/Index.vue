<template>
    <AdminLayout title="Задания" :breadcrumb="[{label: 'Задания'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по номеру и тексту задания"
                       v-model="lFilter.query">

                <Link :href="route('admin.quiz-question.create')" class="btn btn-sm btn-primary ">
                    <i class="fa fa-plus" style="font-size: 0.8em; margin-right: 8px"></i>Добавить
                </Link>
            </div>

            <field label="Профиль">
                <div style="display:flex;">
                    <VueMultiselect :options="profile_options" v-model="profiles" :multiple="true" trackBy="id"
                                    label="name"/>
                </div>
            </field>
            <field label="Направление МКН">
                <div style="display:flex;">
                    <VueMultiselect :options="theme_options" v-model="themes" :multiple="true" trackBy="id"
                                    :disabled="theme_options.length == 0"
                                    placeholder="Все"
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

            <field label="Год">
                <VueMultiselect :options="year_options" v-model="year" trackBy="id"
                                style="width: 200px"
                                placeholder="Все"
                                label="name"/>
            </field>


            <!-- <field label="Статус">
                <div style="display:flex;">
                    <VueMultiselect :options="status_options" v-model="statuses" :multiple="true" trackBy="id" label="name" placeholder="Все"/>
                </div>
            </field> -->
            <br>

            <table class="table">
                <thead class="m-hide">
                <tr>
                    <th class="code">
                        <sort name="id" v-model="lFilter.sort">ID</sort>
                    </th>
                    <!-- <th class="status">
                        <sort name="status" v-model="lFilter.sort">Статус</sort>
                    </th> -->
                    <th class="quiz">
                        <sort name="quiz" v-model="lFilter.sort">Группа</sort>
                    </th>
                    <th class="type">
                        <sort name="type" v-model="lFilter.sort">Тип</sort>
                    </th>
                    <th class="text">Текст задания</th>
                    <th class="order">
                        <sort name="order" v-model="lFilter.sort">Номер задания в тесте</sort>
                    </th>
                    <th class="theme">Направление МКН</th>
                    <th class="weight">
                        <sort name="weight" v-model="lFilter.sort">Макс. балл</sort>
                    </th>
                    <th class="year">
                        <sort name="year" v-model="lFilter.sort">Год</sort>
                    </th>
                    <th class="buttons"></th>
                </tr>
                </thead>
                <tbody>

                <tr v-for="item of items.data" @click="itemClick(item, $event)" class="cursor-pointer"
                    :class="{highlight: item.highlight}">
                    <td class="code">{{ item.id }}</td>
                    <!-- <td class="status">
                        <span class="badge" :class="'badge-' + item.status">{{ item.status_name }}</span>
                    </td> -->
                    <td class="group">
                        {{ item.quiz?.name }}
                    </td>
                    <td class="type" :title="item.type_name">
                        <img :src="'/images/quiz/' + item.type + '.svg'"/>
                    </td>

                    <td class="text">
                        {{ item.snippet }}
                    </td>
                    <td class="order">
                        {{ item.group?.order }}
                    </td>
                    <td class="theme">
                        {{ item.group?.theme?.name }}
                    </td>

                    <td class="weight">
                        {{ item.group.weight }}
                    </td>
                    <td class="year">
                        {{ item.year }}
                    </td>
                    <td class="buttons">
                        <a class="fa fa-times btn-remove" @click.stop="remove(item)" v-if="item.can.delete"></a>
                    </td>
                </tr>
                </tbody>
            </table>
            <table-bottom>
                <pagination :item-count="items.total" v-model="page" :ipp="items.per_page"></pagination>
            </table-bottom>
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";
import Ttd from "@/Components/table-td.vue";
import _debounce from "lodash/debounce.js";
import Sort from "@/Components/Sort.vue";
import Pagination from "@/Components/Paginator.vue";
import TableBottom from "@/Components/TableBottom.vue";
import {countNotEmpty, openEditor, paramsToUrl, selectable, selectables} from "@/Components/utils.js";
import _extend from "lodash/extend.js";
import Checkbox from "@/Components/Checkbox.vue";
import Field from "@/Components/Field.vue";
import VueMultiselect from "vue-multiselect";
import _intersection from "lodash/intersection";
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
        this.theme_id = [];
        this.status = [];
        this.year = [];
        this.sort = null;
        if (defaults) {
            _extend(this, defaults);
        }
    }

    getParams() {
        let params = _extend({},
            this,
            {sort: this.sort ? (this.sort.name + ':' + this.sort.dir) : ''}
        );

        return params;
    }

    count() {
        let count = countNotEmpty(this, ['query', 'profile_id', 'track', 'stage', 'theme_id'])
        return count;
    }
}


export default {
    mixins: [IndexPage],
    components: {Field, Checkbox, TableBottom, Pagination, Sort, Ttd, Link, AdminLayout, VueMultiselect},
    props: {
        filter: Object,
        items: Object,
        profile_options: Array,
        track_options: Array,
        stage_options: Array,
        theme_options: Array,
        status_options: Array,
        year_options: Array
    },
    data() {
        return {
            lFilter: new Filter(this.filter),
            page: this.items.current_page,
        };
    },
    methods: {
        itemClick: openEditor('quiz-question', 'question'),

        refreshPage: _debounce(function (highlightId = null) {

            var $v = this;

            let params = this.lFilter.getParams();
            params.page = this.page;

            axios.get(route(route().current()), {
                params: params, responseType: 'json'
            }).then(function (response) {
                $v.items.data = response.data.items.data;
                $v.items.total = response.data.items.total;
                $v.items.per_page = response.data.items.per_page;
                $v.theme_options.length = 0;
                for (const theme of response.data.theme_options) {
                    $v.theme_options.push(theme);
                }
                params.theme_id = _intersection($v.lFilter.theme_id, $v.theme_options.map(itm => itm.id));
                if (params.theme_id.length != $v.lFilter.theme_id.length) {
                    $v.lFilter.theme_id = params.theme_id;
                }

                $v.highlightItem(highlightId, $v.items.data);

                history.replaceState(null, null, route(route().current()) + '?' + paramsToUrl(params));
                $v.$forceUpdate();
            })
                .catch(function (error) {
                    console.log(error);
                });


        }),
        async remove(item) {
            let index = this.items.data.findIndex(itm => itm.id === item.id);
            let result = await axios.delete(route('admin.quiz-question.destroy', {question: item.id}));
            if (result.data.result == 'ok') {
                this.items.data.splice(index, 1);
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        }
    },
    watch: {
        page() {
            this.refreshPage();
        },
        lFilter: {
            deep: true,
            handler() {
                this.page = 1;
                this.refreshPage();
            }
        }
    },
    computed: {
        profiles: selectables('profile_options', 'lFilter', 'profile_id'),
        themes: selectables('theme_options', 'lFilter', 'theme_id'),
        statuses: selectables('status_options', 'lFilter', 'status'),
        year: selectable('year_options', 'lFilter', 'year')
    }
}
</script>

<style lang="scss">
@import "resources/css/admin-vars.scss";

table.table {
    @include desktop {
        td, th {
            &.code {
                width: 50px;
            }

            &.group {
                width: 150px;
            }

            &.type {
                width: 80px;
                text-align: center;

                img {
                    width: 25px;
                }
            }

            &.order {
                width: 80px;
                text-align: center
            }

            &.theme {
                width: 180px;
            }

            &.weight {
                width: 50px;
                text-align: center;
            }

            &.year {
                width: 80px;
                text-align: right;
            }

            &.buttons {
                width: 50px;
            }

            .badge {
                &-active {
                    background: $success-color;
                    color: white
                }

                &-draft {
                    background: red;
                    color: white
                }
            }
        }
    }
}

</style>

