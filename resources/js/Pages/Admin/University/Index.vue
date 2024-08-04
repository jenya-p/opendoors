<template>
    <AdminLayout title="Университеты" :breadcrumb="[{label: 'Университеты'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по названию"
                       v-model="lItems.filter">
                <Link :href="route('admin.university.create')" class="btn btn-sm btn-primary ">
                    <i class="fa fa-plus" style="font-size: 0.8em; margin-right: 8px"></i>Добавить
                </Link>
            </div>
            <table class="table">
                <thead class="m-hide">
                <tr>
                    <th class="handler-td"></th>
                    <th>Название</th>
                    <th class="table-button"></th>
                    <th class="table-button"></th>
                </tr>
                </thead>
                <tbody v-if="lItems.filter">
                <tr v-for="element of lItems.items" @click="itemClick(element)" class="cursor-pointer">
                    <td></td>
                    <td class="m-title">
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
                <draggable
                    v-else
                    ref="drg"
                    @update="afterReorder"
                    v-model="lItems.items"
                    handle=".handler-td"
                    group="group"
                    item-key="index"
                    ghost-class="ghost"
                    animation="200"
                    tag="tbody"
                >
                    <template #item="{element, index}">
                        <tr @click="itemClick(element)" class="cursor-pointer">
                            <td class="handler-td" :class="{dirty: element.order != index + 1}">
                                <i class=" fa fa-bars handler"></i>
                            </td>
                            <td class="m-title">
                                <div class="primary">{{ element.name }}</div>
                                <div class="secondary">{{ element.name_en }}</div>
                            </td>
                            <td class="table-button">
                                <a @click.stop="changeStatus(element, $event)"
                                   class="item-status" :class="element.status"></a>
                            </td>
                            <td class="buttons">
                                <a class="fa fa-times btn-remove"
                                   @click.stop="remove(element)"></a>
                            </td>
                        </tr>
                    </template>
                </draggable>
            </table>
            <table-bottom align="left" v-if="hasReorderedItems && !lItems.filter">
                <a class="btn btn-xs btn-primary" @click="saveOrder">Сохранить порядок</a>
                <a class="btn btn-xs btn-default" @click="restoreOrder">Отмена</a>
            </table-bottom>
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";
import {SimpleList} from "@/Components/SimpleList.js";
import Ttd from "@/Components/table-td.vue";
import draggable from "vuedraggable";
import TableBottom from "@/Components/TableBottom.vue";

export default {
    components: {TableBottom, Ttd, Link, AdminLayout, draggable},
    props: {
        items: Array
    },
    data() {
        return {
            lItems: new SimpleList(this, {search: ['name', 'name_en']}),
            hasReorderedItems: false
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.university.edit', {university: item.id}))
        },
        async changeStatus(item, ev) {
            let index = this.lItems.items.findIndex(itm => itm.id === item.id);
            ev.target.classList.add('loading');
            let status = (item.status === 'active') ? 'disabled' : 'active';
            let result = await axios.get(route('admin.university.status', {university: item.id, status: status}));
            ev.target.classList.remove('loading');
            if (result.data.result === 'ok') {
                this.lItems.items[index] = result.data.item
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        },
        async saveOrder() {
            let ids = [];
            for (const item of this.lItems.items) {
                ids.push(item.id);
            }
            let result = await axios.put(route('admin.university.update-order'), {orders: ids});
            if (result.data.result == 'ok') {
                this.lItems.items = result.data.items
                this.hasReorderedItems = false;
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        },
        restoreOrder() {
            this.lItems.items.sort((itm1, itm2) => itm1.order - itm2.order);
            this.hasReorderedItems = false;
        },
        async remove(item) {
            let index = this.lItems.items.findIndex(itm => itm.id === item.id);
            let result = await axios.delete(route('admin.university.destroy', {university: item.id}));
            if (result.data.result == 'ok') {
                this.lItems.items = result.data.items;
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
            this.afterReorder()
        },
        afterReorder() {
            for (const index in this.lItems.items) {
                if (parseInt(index) + 1 != this.lItems.items[index].order) {
                    this.hasReorderedItems = true;
                    return
                }
            }
            this.hasReorderedItems = false;
        },
    },
    created() {
        this.lItems.init();
    },

}
</script>

<style lang="scss">
@import "resources/css/admin-vars.scss";

table.table {
    @include mobile {
        td.count .ttd-label {
            width: 150px
        }
    }
}

</style>

