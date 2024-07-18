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
                    <th class="code">
                        <sort name="id" v-model="sort">ID</sort>
                    </th>
                    <th class="quiz">
                        <sort name="quiz" v-model="sort">Группа</sort>
                    </th>
                    <th class="type">
                        <sort name="type" v-model="sort">Тип</sort>
                    </th>
                    <th class="text">Текст задания</th>
                    <th class="order">
                        <sort name="order" v-model="sort">Номер задания в тесте</sort>
                    </th>
                    <th class="theme">Направление МКН</th>
                    <th class="weight">
                        <sort name="weight" v-model="sort">Макс. балл</sort>
                    </th>
                    <th class="buttons"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of lItems" @click="itemClick(item)" class="cursor-pointer">
                    <td class="code">{{ item.id }}</td>
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
                        {{ item.group.order }}
                    </td>
                    <td class="theme">
                        {{ item.group.theme.name }}
                    </td>

                    <td class="weight">
                        {{ item.group.weight }}
                    </td>
                    <td class="buttons">
                        <a class="fa fa-times btn-remove" @click.stop="remove(item)"></a>
                    </td>
                </tr>
                </tbody>
            </table>
            <table-bottom>

                <pagination :item-count="lTotal" v-model="page" :ipp="perPage"></pagination>
            </table-bottom>
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";
import {SimpleList} from "@/Components/SimpleList.js";
import Ttd from "@/Components/table-td.vue";
import _isArray from "lodash/isArray.js";
import _debounce from "lodash/debounce.js";
import Sort from "@/Components/Sort.vue";
import Pagination from "@/Components/Paginator.vue";
import TableBottom from "@/Components/TableBottom.vue";
import {countNotEmpty, findByIds, paramsToUrl, selectables} from "@/Components/utils.js";
import _extend from "lodash/extend.js";
import Checkbox from "@/Components/Checkbox.vue";
import Field from "@/Components/Field.vue";
import VueMultiselect from "vue-multiselect";

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
        if(defaults){
            _extend(this, defaults);
        }
    }

    count() {
        let count = countNotEmpty(this, ['query', 'profile_id', 'track', 'stage', 'theme_id'])
        return count;
    }
}


export default {
    components: {Field, Checkbox, TableBottom, Pagination, Sort, Ttd, Link, AdminLayout, VueMultiselect},
    props: {
        filter: Object,
        items: Object,
        profile_options: Array,
        track_options: Array,
        stage_options: Array,
        theme_options: Array,
    },
    data() {
        let u = new URLSearchParams(document.location.search);
        let page = u.get('page');
        if (page == null) {
            page = 1;
        }

        let sort = u.get('sort');

        if (sort != null) {
            sort = sort.split(':');
            if (_isArray(sort) && sort.length == 2) {
                sort = {name: sort[0], dir: sort[1]};
            } else {
                sort = null;
            }
        }

        return {
            page: 1,
            sort: sort,
            lFilter: new Filter(this.filter),
            lItems: this.items.data,
            lTotal: this.items.total,
            perPage: this.items.per_page
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.quiz-question.edit', {question: item.id}))
        },
        refreshPage: _debounce(function () {

            var $v = this;

            let params = _extend({}, this.lFilter);
            params.sort = this.sort ? (this.sort.name + ':' + this.sort.dir) : null;
            params.page = this.page;

            axios.get(route(route().current()), {
                params: params, responseType: 'json'
            }).then(function (response) {
                $v.lItems = response.data.items.data;
                $v.lTotal = response.data.items.total;
                $v.perPage = response.data.items.per_page;
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
            handler(){
                this.page = 1;
                this.refreshPage();
            }
        },
        sort() {
            this.page = 1;
            this.refreshPage();
        },
    },
    computed: {
        profiles: selectables('profile_options', 'lFilter', 'profile_id'),
        themes: selectables('theme_options', 'lFilter', 'theme_id')
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
                img {
                    width: 25px;
                }

                width: 80px;
                text-align: center
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


            &.buttons {
                width: 50px;
            }
        }
    }


}

</style>

