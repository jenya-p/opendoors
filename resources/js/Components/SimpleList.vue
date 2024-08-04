<template>
    <div>
        <div class="simple-list-filter-wrp">
            <input type="text" class="input" placeholder="Поиск по названию"
                   v-model="lItems.filter">
            <Link :href="getEntityRoute('create')" class="btn btn-sm btn-primary ">
                <i class="fa fa-plus" style="font-size: 0.8em; margin-right: 8px"></i>Добавить
            </Link>
        </div>
        <table class="table">
            <thead class="m-hide">
            <tr>
                <th class="handler-td"></th>
                <slot name="header" :item="lItems.sort"/>
                <th class="table-button"></th>
                <th class="table-button"></th>
            </tr>
            </thead>
            <tbody v-if="lItems.filter">
            <tr v-for="(element, index) of lItems.items" @click="itemClick(element)" class="cursor-pointer">
                <td></td>
                <slot name="body"
                      :item="element"
                      :index="index"
                />
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

                        <slot name="body"
                              :item="element"
                              :index="index"
                        />
                        <td class="table-button">
                            <a @click.stop="changeStatus(element, $event)"
                               class="item-status" :class="element.status"></a>
                        </td>
                        <td class="buttons">
                            <a class="fa fa-times btn-remove"
                               @click.stop="remove(element, $event)"></a>
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
</template>

<script>
import {Link} from "@inertiajs/vue3";
import {SimpleList} from "@/Components/SimpleList.js";
import Ttd from "@/Components/table-td.vue";
import draggable from "vuedraggable";
import TableBottom from "@/Components/TableBottom.vue";

export default {
    components: {TableBottom, Ttd, Link, draggable},
    props: {
        items: Array,
        entity: null,
        searchable: null
    },
    data() {
        return {
            lItems: new SimpleList(this, {search: this.searchable}),
            hasReorderedItems: false,
        };
    },
    methods: {
        getEntityRoute(subRoute, item = null, params = {}){
            if(item){
                params[this.entity.replace('-', '_')] = item.id;
            }
            return route('admin.' + this.entity + '.' + subRoute, params)
        },
        itemClick: function (item) {
            if(!item.hasOwnProperty('can') || item.can.update){
                this.$inertia.visit(this.getEntityRoute('edit', item));
            } else {
                this.$inertia.visit(this.getEntityRoute('show', item));
            }
        },
        async changeStatus(item, ev) {
            let index = this.lItems.items.findIndex(itm => itm.id === item.id);
            ev.target.classList.add('loading');
            let status = (item.status === 'active') ? 'disabled' : 'active';
            let result = await axios.get(this.getEntityRoute('status', item, {status: status}));
            ev.target.classList.remove('loading');
            if (result.data.result === 'ok') {
                if(result.data.items){
                    this.lItems.items = result.data.items
                } else {
                    this.lItems.items[index].status = status;
                }
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        },
        async saveOrder() {
            let ids = [];
            for (const item of this.lItems.items) {
                ids.push(item.id);
            }
            let result = await axios.put(this.getEntityRoute('update-order'), {orders: ids});
            if (result.data.result == 'ok') {
                if(result.data.items){
                    this.lItems.items = result.data.items
                } else {
                    for (const index in this.lItems.items) {
                        this.lItems.items[index].order = parseInt(index) + 1;
                    }
                }
                this.hasReorderedItems = false;
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        },
        restoreOrder() {
            this.lItems.items.sort((itm1, itm2) => itm1.order - itm2.order);
            this.hasReorderedItems = false;
        },
        async remove(item, ev) {
            let index = this.lItems.items.findIndex(itm => itm.id === item.id);
            ev.target.classList.add('loading');
            let result = await axios.delete(this.getEntityRoute('destroy', item));
            ev.target.classList.remove('loading');
            if (result.data.result == 'ok') {
                if(result.data.items){
                    this.lItems.items = result.data.items
                } else {
                    this.lItems.items.splice(index, 1);
                    for (const index2 in this.lItems.items) {
                        this.lItems.items[index2].order = parseInt(index2) + 1;
                    }
                    this.hasReorderedItems = false;
                    this.$forceUpdate();
                }
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


