<template>
    <AdminLayout title="Страны" :breadcrumb="[{label: 'Страны'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по названию"
                       v-model="lItems.filter">
                <Link :href="route('admin.dir-country.create')" class="btn btn-sm btn-primary ">
                    <i class="fa fa-plus" style="font-size: 0.8em; margin-right: 8px"></i>Добавить
                </Link>
            </div>

            <table class="table">
                <thead class="m-hide">
                <tr>
                    <th>Название</th>
                    <th>Код</th>
                    <th class="buttons"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of lItems.items" @click="itemClick(item)" class="cursor-pointer">
                    <td class="m-title">
                        <div class="primary">{{ item.name }}</div>
                        <div class="secondary">{{ item.name_en }}</div>
                    </td>
                    <td>{{item.code}}</td>
                    <td class="buttons">
                        <a class="fa fa-times btn-remove" @click.stop="remove(item)"></a>
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
        return {
            lItems: new SimpleList(this, {search: ['name', 'name_en', 'code']}),
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.dir-country.edit', {country: item.id}))
        },

        async remove(item) {
            let index = this.items.findIndex(itm => itm.id === item.id);
            let result = await axios.delete(route('admin.dir-country.destroy', {country: item.id}));
            if (result.data.result == 'ok') {
                this.items.splice(index, 1);
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
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

