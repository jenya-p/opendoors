<template>
    <AdminLayout title="Новости" :breadcrumb="[{label: 'Новости'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по заголовку"
                       v-model="filter">
                <Link :href="route('admin.news.create')" class="btn btn-sm btn-primary ">
                    <i class="fa fa-plus" style="font-size: 0.8em; margin-right: 8px"></i>Добавить
                </Link>
            </div>

            <table class="table">
                <thead class="m-hide">
                <tr>
                    <th class="code"><sort name="id" v-model="sort" >№</sort></th>
                    <th class="quiz"><sort name="date" v-model="sort" >Дата</sort></th>
                    <th class="type"><sort name="title" v-model="sort" >Заголовок</sort></th>
                    <th class="buttons"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of items.data" @click="itemClick(item)" class="cursor-pointer">
                    <td class="id">{{item.id}}</td>
                    <td class="date">
                        {{ $filters.date(item.date, 'dd MMM yyyy')}}
                    </td>

                    <td class="title">
                        {{item.title}}
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
            page: page,
            sort: sort,
            filter: filter
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.news.edit', {news: item.id}))
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
            let result = await axios.delete(route('admin.news.destroy', {question: item.id}));
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
            &.id {
                width: 50px;
            }
            &.date{
                width: 120px;
            }
            &.title{}
            &.buttons {
                width: 50px;
            }
        }
    }
}

</style>

