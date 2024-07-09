<template>
    <AdminLayout title="Участники" :breadcrumb="['Участники']">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по имени и email"
                       v-model.lazy="filter">
                <Link :href="route('admin.student.create')" class="btn btn-sm btn-primary ">
                    <i class="fa fa-plus" style="font-size: 0.8em; margin-right: 8px"></i>Добавить
                </Link>
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th>
                        Аккаунт
                    </th>
                    <th>
                        Уровень образования
                    </th>
                    <th>
                        Гражданство
                    </th>
                    <th>
                        <sort name="created_at" v-model="sort">Дата создания</sort>
                    </th>
                    <th class="buttons">

                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of items.data" @click="itemClick(item)" class="cursor-pointer">
                    <td>
                        <div>{{ item.user.name }}</div>
                        <div class="secondary">{{ item.user.email }}</div>
                    </td>
                    <td>
                        {{item.edu_level.name}}
                    </td>
                    <td>
                        {{item.citizenship}}
                    </td>
                    <td>
                        {{
                            $filters.date(item.created_at)
                        }}
                    </td>
                    <td class="buttons">
                        <a class="fa fa-times btn-remove" @click.stop="remove(item)"></a>
                    </td>
                </tr>
                </tbody>
            </table>

            <table-bottom>
                <paginator :item-count="items.total" v-model="page" :ipp="items.per_page"></paginator>
            </table-bottom>

        </div>



    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";
import Paginator from "@/Components/Paginator.vue";
import Sort from "@/Components/Sort.vue";
import _debounce from "lodash/debounce";
import _isArray from "lodash/isArray";
import TableBottom from "@/Components/TableBottom.vue";
import Ttd from "@/Components/table-td.vue";



export default {
    components: {TableBottom, Sort, Paginator, Link, AdminLayout},
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
            this.$inertia.visit(route('admin.student.edit', {student: item.id}))
        },
        async remove(item) {
            let index = this.items.data.findIndex(itm => itm.id === item.id);
            let result = await axios.delete(route('admin.student.destroy', {student: item.id}));
            if (result.data.result == 'ok') {
                this.items.data.splice(index, 1);
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
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
        })
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
@import "resources/css/admin-vars";
.role-badge{
    display: inline-block;
    padding: 0 0.6em;
    border-radius: 4px;
    background-color: $base-color;
    color: white;
    line-height: 1.6em;
    font-size: 0.9em;

}
.badges{display: flex; gap: 5px 10px; flex-wrap: wrap;
    align-items: center;}
</style>

