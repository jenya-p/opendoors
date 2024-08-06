<template>
    <AdminLayout :breadcrumb="[
        {link: route('lk.dashboard'), label: $t('Личный кабинет')},
        $t('Мотивационное письмо')
        ]" :title="$t('Мотивационное письмо')">


        <div class="block">
            <p class="info">{{ $t('info') }}</p>

            <table class="items-table">
                <tr v-for="member of participant.members" :class="{filled: member.statement}">
                    <td class="check">
                        <i class="fa fa-check" v-if="member.statement"></i>
                    </td>
                    <td class="label">
                        {{ member.profile.name }}
                        <div class="secondary">{{ member.track.name }}</div>
                    </td>
                    <td class="button">
                        <template v-if="member.statement">

                            <a class="fa fa-pencil btn-edit" @click.stop="itemClick(member.statement)"></a>
                            <a class="fa fa-times btn-remove" @click.stop="remove(member)"></a>
                        </template>
                        <Link v-else :href="route('lk.statement.create', {member_id: member.id})" class="btn btn-s btn-primary" >
                            {{ $t('Заполнить') }}
                        </Link>
                    </td>
                </tr>
            </table>
        </div>



    </AdminLayout>
</template>
<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";

export default {
    components: {AdminLayout, Link},
    props: ['participant', 'items'],
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('lk.statement.edit', {statement: item.id}))
        },
        async remove(member) {
            if(member.statement == null) return;
            let result = await axios.delete(route('lk.statement.destroy', {statement: member.statement.id}));
            if (result.data.result == 'ok') {
                member.statement = null;
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        },
    },

}

</script>

<style lang="scss" scoped>
@import "resources/js/Pages/Lk/styles";
.secondary{
    font-size: 0.8em;
}
td.check{
    width: 45px;
    .fa-check {color: $success-color; font-size: 1.4em;}
}

td.button{
    text-align: right;
}
</style>

<i18n src="/resources/js/Pages/Lk/translations.json"/>
<i18n>
{
    "ru": {
        "info": "Необходимо заполнить мотивационное письмо для каждого из выбранных вами профилей Олимпиады.",
       "Заполнить мотивационное письмо" : "Заполнить мотивационное письмо",
        "Заполнить": "Заполнить"


    },
    "en": {
        "info": "You can add information about a maximum of four received and/or currently received formations.",
       "Заполнить мотивационное письмо" : "Add motivation letter",
        "Заполнить": "Add"

    }
}
</i18n>
