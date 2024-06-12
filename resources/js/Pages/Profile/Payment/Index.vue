<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import ProfileTabs from "@/Pages/Profile/Partials/ProfileTabs.vue";
import Field from "@/Components/Field.vue";
import {useForm, usePage, Link} from "@inertiajs/vue3";

export default {
    components: {Link, Field, ProfileTabs, GuestLayout},
    props: {
        items: {
            type: Array, default: []
        }
    },
    data: () => {
        let user = usePage().props.auth.user;
        return {
            form: useForm({
                name: user.name,
                email: user.email,
            })
        }
    },
    methods: {
        itemClick(item){
            this.$inertia.visit(route('profile-payment.show', {payment: item.id}))
        }
    }
}


</script>

<template>
    <GuestLayout wrapper-class="profile-page">

        <div class="block">
            <ProfileTabs/>
        </div>
        <div class="block">

            <div class="tabs-fake" style="margin-bottom: 10px">
                <span>История операций</span>
            </div>

            <div class="table-wrapper" v-if="items.length">

                <table class="table">
                    <thead>
                    <tr>
                        <th>Число</th>
                        <th>Операция</th>
                        <th class="to-right">Сумма</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="item of items" class="cursor-pointer" @click="itemClick(item)" >
                        <td class="font-inter">
                            <span class="m-label">Число: </span><span class="nobr">{{ $filters.date(item.created_at) }}</span>
                        </td>
                        <td class="m-title">Покупка ({{ item.description }})</td>
                        <td class="font-inter to-right">
                            <span class="m-label">Сумма: </span><span class="color-red bold nobr">{{  $filters.currency(item.amount, 2) }} ₽</span>
                        </td>
                    </tr>
                    </tbody>

                </table>

            </div>
            <div v-else class="empty-table">
                <p>В данный момент у вас нет активных тестов</p>
                <Link href="/prices" class="btn btn-sm btn-default">Купить</Link>
            </div>
        </div>
    </GuestLayout>
</template>

<style lang="scss" scoped>

</style>
