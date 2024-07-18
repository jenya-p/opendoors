<template>
    <AdminLayout title="Группы заданий" :breadcrumb="[{label: 'Группы заданий'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по профилю, треку и этапу"
                       v-model="lItems.filter">
            </div>

            <table class="table">
                <thead class="m-hide">
                <tr>
                    <th class="profile"><sort name="name" v-model="lItems.sort">Профиль</sort></th>
                    <th class="track"><sort name="track" v-model="lItems.sort">Трек</sort></th>
                    <th class="stage"><sort name="stage" v-model="lItems.sort">Этап</sort></th>
                    <th class="question-count"><sort name="question_count" v-model="lItems.sort" strategy="numeric">Варианты</sort></th>
                    <th class="group-count"><sort name="group_count" v-model="lItems.sort" strategy="numeric">Задания</sort></th>
                    <th class="buttons"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of lItems.items" @click="itemClick(item)" class="cursor-pointer">
                    <td class="profile">
                       {{ item.profile.name }}
                    </td>
                    <td class="track">
                        {{ item.track_name }}
                    </td>
                    <td class="stage">
                        {{ item.stage_name }}
                    </td>
                    <td class="question-count">
                        {{item.question_count}}
                    </td>
                    <td class="group-count">
                        {{item.group_count}}
                    </td>
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
import Sort from "@/Components/Sort.vue";



export default {
    components: {Sort, Ttd, Link, AdminLayout},
    props: {
        items: Array
    },
    data() {
        return{
            lItems: new SimpleList(this, {search: ['name']}),
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.quiz.edit', {quiz: item.id}))
        },

        async remove(item) {
            let index = this.items.findIndex(itm => itm.id === item.id);
            let result = await axios.delete(route('admin.quiz.destroy', {quiz: item.id}));
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

<style lang="scss" scoped>
@import "resources/css/admin-vars.scss";

.table {
    th, td{
        &.profile{
        }
        &.question-count,
        &.group-count{
            width: 85px; text-align: center;
        }
    }
}

</style>

