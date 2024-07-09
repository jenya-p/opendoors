<template>
    <AdminLayout title="График" :breadcrumb="[{label: 'График'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по заголовку"
                       v-model="lItems.filter">
                <Link :href="route('admin.schedule.create')" class="btn btn-sm btn-primary ">
                    <i class="fa fa-plus" style="font-size: 0.8em; margin-right: 8px"></i>Добавить
                </Link>
            </div>

            <table class="table">
                <thead class="m-hide">
                <tr>
                    <th>Начало</th>
                    <th>Завершение</th>
                    <th>Заголовок</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of lItems.items" @click="itemClick(item)" class="cursor-pointer">
                    <td label="date">
                        {{$filters.date(item.date_from, 'dd.MM.yyyy')}}
                    </td>
                    <td label="date">
                        {{$filters.date(item.date_to, 'dd.MM.yyyy')}}
                    </td>
                    <td class="title">
                        {{ item.title }}
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
import {SimpleList} from "@/Components/SimpleList.js";
import Ttd from "@/Components/table-td.vue";



export default {
    components: {Ttd, Link, AdminLayout},
    props: {
        items: Array
    },
    data() {
        return{
            lItems: new SimpleList(this, {search: ['name', 'title']}),
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.schedule.edit', {schedule: item.id}))
        }
    },
    created() {
        this.lItems.init();
    }

}
</script>

<style lang="scss">
@import "resources/css/admin-vars.scss";

</style>

