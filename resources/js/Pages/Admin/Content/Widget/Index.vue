<template>
    <AdminLayout title="Виджеты" :breadcrumb="[{label: 'Виджеты'}]">

        <div class="block">

           <table class="table">
                <thead class="m-hide">
                <tr>
                    <th>Код</th>
                    <th>Название</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of lItems.items" @click="itemClick(item)" class="cursor-pointer">
                    <td>
                        {{ item.key }}
                    </td>
                    <td>
                        {{ item.name }}
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
            lItems: new SimpleList(this, {search: ['id']}),
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.widget.edit', {widget: item.id}))
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

