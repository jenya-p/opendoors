<template>
    <AdminLayout title="Контент" :breadcrumb="[{label: 'Контент'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по названию"
                       v-model="lItems.filter">
            </div>

            <table class="table">
                <thead class="m-hide">
                <tr>
                    <th>id</th>
                    <th>Заголовок</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of lItems.items" @click="itemClick(item)" class="cursor-pointer">
                    <ttd label="id">
                        {{ item.id }}
                    </ttd>
                    <td class="m-title">
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
import {SimpleList} from "@/Components/SimpleList";
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
            this.$inertia.visit(route('admin.content.edit', {content: item.id}))
        }
    },
    created() {
        this.lItems.init();
    }

}
</script>

<style lang="scss">
@import "resources/css/admin-vars";
@include mobile{
    .m-title{order: -1}
}
</style>

