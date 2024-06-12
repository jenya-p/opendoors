<template>
    <GuestLayout wrapper-class="profile-page">

        <div class="block">

            <div class="tabs-fake" style="margin-bottom: 10px">
                <span>Подтверждение заказа</span>
            </div>

            <div class="block-bordered">
                <div class="table-wrapper">

                    <table class="table">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Блок вопросов</th>
                            <th>Сумма</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item,index) of items">
                            <td class="m-hide">{{ index + 1 }}</td>
                            <ttd class="m-title">{{ item.title }}</ttd>
                            <ttd label="Цена">{{ $filters.currency(item.amount, 'auto') }}</ttd>
                        </tr>
                        </tbody>
                    </table>
                    <field label="Сумма">
                        <span type="text" class="input">{{ $filters.currency(amount, 'auto') }} ₽</span>
                    </field>
                    <div class="after-payment-action">
                        <Link class="btn btn-default" @click="submit">Перейти к оплате</Link>
                    </div>


                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Field from "@/Components/Field.vue";
import {Link} from "@inertiajs/vue3";
import Ttd from "@/Components/table-td.vue";
import _isEmpty from "lodash/isEmpty.js";

export default {
    components: {Ttd, Field, GuestLayout, Link},
    props: ['items', 'amount'],

    data() {
        return {}
    },
    methods: {
        async submit(){
            let result = await axios.post(route('profile-payment.store'), {ids: this.items.map(itm => itm.id)});
            if (result.data.result == 'ok' && !_isEmpty(result.data.redirect_to)) {
                document.location = result.data.redirect_to;
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        }
    },
    computed: {}

}

</script>

<style lang="scss" scoped>
.after-payment-action {
    margin-top: 50px;
    text-align: center;

    a {
        width: auto;
    }

}
.field{
    margin-top: 35px;
}
span.input{
    font-family: 'Inter', sans-serif, serif;
    width: 150px;
    text-align: left;
    padding-left: 20px;
    display: inline-block;
}

</style>
