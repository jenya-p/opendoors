<template>
    <AdminLayout title="Задания" :breadcrumb="[{label: 'Задания'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по номеру и тексту задания"
                       v-model="filter">
                <Link :href="route('admin.quiz-question.create')" class="btn btn-sm btn-primary ">
                    <i class="fa fa-plus" style="font-size: 0.8em; margin-right: 8px"></i>Добавить
                </Link>
            </div>

            <table class="table">
                <thead class="m-hide">
                <tr>
                    <th class="code"><sort name="id" v-model="sort" >№</sort></th>
                    <th class="quiz"><sort name="quiz" v-model="sort" >Группа</sort></th>
                    <th class="type"><sort name="type" v-model="sort" >Тип</sort></th>
                    <th class="text">Текст задания</th>
                    <th class="order"><sort name="order" v-model="sort" >Порядок</sort></th>
                    <th class="weight"><sort name="weight" v-model="sort" >Вес</sort></th>
                    <th class="options">Варианты</th>
                    <th class="buttons"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of items.data" @click="itemClick(item)" class="cursor-pointer">
                    <td class="code">{{item.id}}</td>
                    <td class="group">
                        {{item.quiz?.name}}
                    </td>
                    <td class="type" :title="item.type_name">
                        <img :src="'/images/quiz/' + item.type + '.svg'" />
                    </td>

                    <td class="text">
                        {{ item.snippet }}
                    </td>
                    <td class="order">
                        {{item.group.order}}
                    </td>
                    <td class="weight">
                        {{item.group.weight}}
                    </td>
                    <td class="options">
                        {{item.option_count}}
                    </td>
                    <td class="buttons">
                        <a class="fa fa-times btn-remove" @click.stop="remove(item)"></a>
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
import {SimpleList} from "@/Components/SimpleList.js";
import Ttd from "@/Components/table-td.vue";
import _isArray from "lodash/isArray.js";
import _debounce from "lodash/debounce.js";
import Sort from "@/Components/Sort.vue";
import Pagination from "@/Components/Paginator.vue";
import TableBottom from "@/Components/TableBottom.vue";



export default {
    components: {TableBottom, Pagination, Sort, Ttd, Link, AdminLayout},
    props: {
        items: Object
    },
    data() {
        let u = new URLSearchParams(document.location.search);
        let page = u.get('page');
        if(page == null){
            page = 1;
        }
        let filter = u.get('filter');
        let sort = u.get('sort');

        if(sort != null){
            sort = sort.split(':');
            if(_isArray(sort) && sort.length == 2){
                sort = {name: sort[0], dir: sort[1]};
            } else {
                sort = null;
            }
        }

        return{
            page: 1,
            sort: sort,
            filter: filter
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.quiz-question.edit', {question: item.id}))
        },
        refreshPage: _debounce(function(){
            var $v = this;
            let sort = this.sort ? (this.sort.name + ':' + this.sort.dir) : '';
            this.$inertia.reload({
                method: 'get',
                replace: true,
                only: ['items'],
                data: {
                    page: this.page,
                    filter: this.filter,
                    sort: sort
                }
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
        page(){
            this.refreshPage();
        },
        filter(){
            this.page = 1;
            this.refreshPage();
        },
        sort(){
            this.page = 1;
            this.refreshPage();
        },
    },

}
</script>

<style lang="scss">
@import "resources/css/admin-vars.scss";

table.table{
    @include desktop{
        td, th {
            &.group {
                width: 150px;
            }
            &.type{
                img{
                    width: 25px;
                }
                width: 80px; text-align: center
            }
            &.order{width: 80px; text-align: center}
            &.weight{
                width: 50px;
                text-align: center;
            }
            &.options{
                width: 80px; text-align: center
            }
            &.buttons {
                width: 50px;
            }
        }
    }


}

</style>

