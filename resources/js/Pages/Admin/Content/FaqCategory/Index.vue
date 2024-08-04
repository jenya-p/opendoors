<template>
    <AdminLayout title="Категории FAQ" :breadcrumb="[{label: 'Категории FAQ'}]">

        <simple-list :items="items"
            entity="faq-category"
            :searchable="['name', 'name_en']">
            <template #header="props">
                <th class="name">
                    <sort name="name" v-model="props.sort" >Название</sort>
                </th>
                <th class="count">
                    <sort name="faqs_count" v-model="props.sort" >Кол-во</sort>
                </th>
            </template>
            <template #body="props">
                <td class="name">{{props.item.name}}</td>
                <td class="count">{{props.item.faqs_count}}</td>
            </template>
        </simple-list>

    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import SimpleList from "@/Components/SimpleList.vue";
import Sort from "@/Components/Sort.vue";


export default {
    components: {Sort, SimpleList, AdminLayout},
    props: {
        items: Array
    },
}
</script>

<style lang="scss">
@import "resources/css/admin-vars.scss";

table.table {
    td, th{
        &.count{
            width: 80px; text-align: center;
        }
    }
}

</style>

