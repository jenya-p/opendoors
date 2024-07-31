<template>
    <AdminLayout title="Уровни образования" :breadcrumb="[{label: 'Уровни образования'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по названию"
                       v-model="lItems.filter">
                <Link :href="route('admin.edu-level.create')" class="btn btn-sm btn-primary ">
                    <i class="fa fa-plus" style="font-size: 0.8em; margin-right: 8px"></i>Добавить
                </Link>
            </div>

            <table class="table">
                <thead class="m-hide">
                <tr>
                    <th class="order">Номер п/п</th>
                    <th>Название</th>
                    <th class="booleans">Многократное</th>
                    <th class="booleans">Диплом</th>
                    <th class="table-buttons"></th>
                    <th class="table-buttons"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of lItems.items" @click="itemClick(item)" class="cursor-pointer">
                    <td class="order">
                        {{item.order}}
                    </td>
                    <td class="m-title">
                        <div class="primary">{{ item.name }}</div>
                        <div class="secondary">{{ item.name_en }}</div>
                    </td>
                    <td class="booleans">
                        {{item.multiple ? 'Да' : ''}}
                    </td>
                    <td class="booleans">
                        {{item.diploma ? 'Да' : ''}}
                    </td>
                    <td class="table-button">
                        <a @click.stop="changeStatus(item, $event)" class="item-status" :class="item.status"></a>
                    </td>
                    <td class="table-buttons">
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
        return{
            lItems: new SimpleList(this, {search: ['name', 'name_en']}),
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.edu-level.edit', {'edu_level': item.id}))
        },
        async changeStatus(item, ev) {
            let index = this.items.findIndex(itm => itm.id === item.id);
            ev.target.classList.add('loading');
            let status = (item.status === 'active') ? 'disabled': 'active';
            let result = await axios.get(route('admin.edu-level.status', {edu_level: item.id, status: status}));
            ev.target.classList.remove('loading');
            if (result.data.result === 'ok') {
                this.items[index] = result.data.item
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        },
        async remove(item) {
            let index = this.items.findIndex(itm => itm.id === item.id);
            let result = await axios.delete(route('admin.edu-level.destroy', {edu_level: item.id}));
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

table.table{
    @include mobile{
        td.count .ttd-label {width: 150px}
    }

    td,th{
        &.booleans{width: 120px; text-align: center}
        &.order{width: 50px; text-align: center}
    }

}

</style>

