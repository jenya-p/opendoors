<template>
    <AdminLayout title="Этапы" :breadcrumb="[{label: 'Этапы'}]">

        <div class="block">

            <div v-field-container class="list-filter-wrp">
                <field label="Профиль">
                    <div style="display:flex;">
                        <VueMultiselect :options="profile_options" v-model="profiles" :multiple="true" trackBy="id"
                                        label="name"/>
                    </div>
                </field>
                <field label="Трек" class="field-checkboxes">
                    <checkbox v-for="(track) of track_options" v-model="lFilter.track_id" :value="track.id">
                        {{ track.name }}
                    </checkbox>
                </field>
                <field label="Тип этапа" class="field-checkboxes">
                    <checkbox v-for="(type) of type_options" v-model="lFilter.type" :value="type.id">{{ type.name }}
                    </checkbox>
                </field>
            </div>

            <table class="table">
                <thead class="m-hide">
                <tr>
                    <th>
                        <sort name="order" v-model="sort">№ п/п</sort>
                    </th>
                    <th>
                        <sort name="profile" v-model="sort">Профиль</sort>
                    </th>
                    <th>
                        <sort name="track" v-model="sort">Трек</sort>
                    </th>
                    <th>
                        <sort name="type" v-model="sort">Тип этапа</sort>
                    </th>
                    <th class="buttons"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of lItems" @click="itemClick(item)" class="cursor-pointer">
                    <td>
                        {{ item.order }}
                    </td>
                    <td>
                        {{ item.profile?.name }}
                    </td>
                    <td>
                        {{ item.track.name }}
                    </td>
                    <td>
                        {{ item.type_name }}
                    </td>
                    <td class="buttons">
                        <a class="fa fa-times btn-remove" @click.stop="remove(item)"></a>
                    </td>
                </tr>
                </tbody>
            </table>
            <table-bottom>
                <pagination :item-count="total" v-model="page" :ipp="perPage"></pagination>
            </table-bottom>

        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link, router} from "@inertiajs/vue3";
import Ttd from "@/Components/table-td.vue";
import Field from "@/Components/Field.vue";
import Sort from "@/Components/Sort.vue";
import VueMultiselect from "vue-multiselect";
import {findByIds, countNotEmpty, paramsToUrl} from "@/Components/utils";
import Checkbox from "@/Components/Checkbox.vue";
import TableBottom from "@/Components/TableBottom.vue";
import Pagination from "@/Components/Paginator.vue";
import _debounce from "lodash/debounce";
import _extend from "lodash/extend";


class Filter {
    constructor() {
        this.track_id = [];
        this.profile_id = [];
        this.type = [];
    }

    count() {
        let count = countNotEmpty(this, ['profile_ids', 'track_ids', 'types'])
        return count;
    }
}


export default {
    components: {Pagination, TableBottom, Checkbox, Sort, Field, Ttd, Link, AdminLayout, VueMultiselect},
    props: {
        filter: Object,
        items: Object,
        track_options: Array,
        profile_options: Array,
        type_options: Array
    },
    data() {
        return {
            sort: null,
            page: 1,
            lItems: this.items.data,
            total: this.items.total,
            perPage: this.items.per_page,
            lFilter: new Filter(this.filter, this.sorts),
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.stage.edit', {stage: item.id}))
        },

        async remove(item) {
            let index = this.items.findIndex(itm => itm.id === item.id);
            let result = await axios.delete(route('admin.stage.destroy', {stage: item.id}));
            if (result.data.result == 'ok') {
                this.items.splice(index, 1);
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        },
        refreshPage: _debounce(function () {
            var $v = this;

            let params = _extend({}, this.lFilter);
            params.sort = this.sort ? (this.sort.name + ':' + this.sort.dir) : null;
            params.page = this.page;

            axios.get(route(route().current()), {
                params: params, responseType: 'json'
            }).then(function (response) {

                $v.lItems = response.data.items.data;
                $v.total = response.data.items.total;
                $v.perPage = response.data.items.per_page;
                history.replaceState(null, null, route(route().current()) + '?' + paramsToUrl(params));
                $v.$forceUpdate();
            })
                .catch(function (error) {
                    console.log(error);
                });

        }),

    },
    watch: {
        page() {
            this.refreshPage();
        },
        lFilter: {
            deep: true,
            handler() {
                this.page = 1;
                this.refreshPage();
            }
        },
        sort() {
            this.page = 1;
            this.refreshPage();
        },
    },

    computed: {
        profiles: {
            get() {
                return findByIds(this.profile_options, this.lFilter.profile_id);
            },
            set(value) {
                this.lFilter.profile_id = value.map(itm => itm.id);
            }
        }
    }

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

