<template>
    <GuestLayout wrapper-class="profile-page">
        <div class="block">
            <ProfileTabs/>
        </div>
        <div class="block">

            <div class="tabs-fake" style="margin-bottom: 10px">
                <Link :href="route('profile-payment.index')">История операций</Link>
                ›
                <span>Покупка №{{payment.id}} от {{$filters.date(payment.created_at)}}</span>
            </div>

            <div class="block-bordered">
                <div class="table-wrapper">

                    <p class="success-message color-green" v-if="show_status && payment.status == 'done'">Оплата прошла успешно</p>
                    <p class="declined-message color-red"  v-if="show_status && payment.status == 'canceled'">Оплата отклонена</p>
                    <p class="declined-message color-red"  v-if="show_status && payment.status == 'new'">Оплата была прервана</p>

                    <table class="table">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Блок вопросов</th>
                            <th>Сумма</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item,index) of payment.items">
                            <td>{{index + 1}}</td>
                            <td>{{item.title}}</td>
                            <td>{{$filters.currency(item.amount, 'auto')}}</td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2" style="text-align: right; padding-right: 20px">Сумма:</td>
                            <td>
                                {{$filters.currency(payment.amount, 'auto')}}
                            </td>
                        </tr>
                        </tfoot>
                    </table>

                    <div v-if="show_status" class="after-payment-action">
                        <Link :href="route('test', {test: payment.items[0].test_id})" class="btn btn-default" v-if="payment.status=='done' && payment.items.length == 1">Преступить к тестированию</Link>
                        <Link :href="route('profile-test.index')" class="btn btn-default" v-else-if="payment.status=='done' && payment.items.length > 1">Доступные тесты</Link>
                        <Link :href="route('prices')" class="btn btn-default" v-else-if="payment.status=='canceled'">Каталог тестов</Link>
                    </div>
                    <div v-else-if="confirmation_url" class="after-payment-action">
                        <a class="btn btn-default" :href="confirmation_url">Оплата</a>
                    </div>


                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Field from "@/Components/Field.vue";
import currency from "@/Filters/currency.js";
import {Link} from "@inertiajs/vue3";
import ProfileTabs from "@/Pages/Profile/Partials/ProfileTabs.vue";

export default {
    components: {ProfileTabs, Field, GuestLayout, Link},
    props: ['payment', 'show_status', 'confirmation_url'],

    data(){
        return {

        }
    },
    methods: {
        currency: currency
    },
    computed: {

    }

}

</script>

<style lang="scss" scoped>

.success-message, .declined-message{font-size: 1.3em; text-align: center; margin-top: 15px}

.after-payment-action{
    margin-top: 50px; text-align: center;
    a{
        width: auto;
    }
}
</style>
