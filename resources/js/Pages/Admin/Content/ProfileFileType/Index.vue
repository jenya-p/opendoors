<template>
    <AdminLayout title="Типы файлов в профилях" :breadcrumb="[{label: 'Типы файлов в профилях'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по названию" v-model="lItems.filter">
                <Link :href="route('admin.profile-file-type.create')" class="btn btn-sm btn-primary ">
                    <i class="fa fa-plus" style="font-size: 0.8em; margin-right: 8px"></i>Добавить
                </Link>
            </div>

            <table class="table">
                <thead class="m-hide">
                <tr>
                    <th>
                        <sort name="type" v-model="lItems.sort">Раздел</sort>
                    </th>
                    <th>
                        <sort name="name" v-model="lItems.sort">Название</sort>
                    </th>
                    <th>Треки</th>
                    <th>
                        <sort name="file_count" v-model="lItems.sort" strategy="numeric">Файлов</sort>
                    </th>
                    <th class="buttons"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of lItems.items" @click="itemClick(item)" class="cursor-pointer">
                    <td>{{ item.type_name }}</td>
                    <td>
                        <div class="primary">{{ item.name }}</div>
                        <div class="secondary">{{ item.name_en }}</div>
                    </td>
                    <td>
                        {{ item.tracks.map(itm => itm.name).join(', ') }}
                    </td>
                    <td>
                        {{ item.file_count }}
                    </td>
                    <td class="button">
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
import Sort from "@/Components/Sort.vue";


export default {
    components: {Sort, Ttd, Link, AdminLayout},
    props: {
        items: Array
    },
    data() {
        return {
            lItems: new SimpleList(this, {search: ['name']}),
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.profile-file-type.edit', {profile_file_type: item.id}))
        },

        async remove(item) {
            let index = this.items.findIndex(itm => itm.id === item.id);
            try {
                let result = await axios.delete(route('admin.profile-file-type.destroy', {profile_file_type: item.id}));
                if (result.data.result == 'ok') {
                    this.items.splice(index, 1);
                } else {
                    alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
                }
            } catch (error) {
                if (error && error.response && error.response.data && error.response.data.message) {
                    alert(error.response.data.message);
                } else {
                    alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
                }
            }
        },


    },
    created() {
        this.lItems.init();
    }
}
</script>

<style lang="scss">
@import "resources/css/admin-vars.scss";

@include mobile {
    .m-title {
        order: -1
    }
}
</style>

