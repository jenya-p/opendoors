<template>
    <AdminLayout title="FAQ" :breadcrumb="[{label: 'FAQ'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по названию"
                       v-model="lItems.filter">
                <Link :href="route('admin.faq.create')" class="btn btn-sm btn-primary ">
                    <i class="fa fa-plus" style="font-size: 0.8em; margin-right: 8px"></i>Добавить
                </Link>
            </div>
            <table class="table" v-if="lItems.filter">
                <thead class="m-hide">
                <tr>
                    <th>Категория</th>
                    <th class="table-button"></th>
                    <th class="table-button"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="element of lItems.items" @click="itemClick(item)" class="cursor-pointer">
                    <td >
                        <div class="primary">{{ element.name }}</div>
                        <div class="secondary">{{ element.name_en }}</div>
                    </td>
                    <td class="table-button">
                        <a @click.stop="changeStatus(element, $event)" class="item-status" :class="element.status"></a>
                    </td>
                    <td class="table-button">
                        <a class="fa fa-times btn-remove" @click.stop="remove(element)"></a>
                    </td>
                </tr>
                </tbody>
            </table>
            <template v-else>

            </template>


        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";
import {SimpleList} from "@/Components/SimpleList.js";
import Ttd from "@/Components/table-td.vue";
import Sort from "@/Components/Sort.vue";



export default {
    components: {Sort, Ttd, Link, AdminLayout},
    props: {
        items: Array
    },
    data() {
        return{
            lItems: new SimpleList(this, {search: ['question']}),
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.faq.edit', {faq: item.id}))
        }
    },
    created() {
        this.lItems.init();
    }

}
</script>

<style lang="scss">
@import "resources/css/admin-vars.scss";
@include mobile{
    .m-title{order: -1}
}
</style>

