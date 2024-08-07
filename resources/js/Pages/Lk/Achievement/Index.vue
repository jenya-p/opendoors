<template>
    <AdminLayout :breadcrumb="[
        {link: route('lk.dashboard'), label: $t('Личный кабинет')},
        $t('Достижения')
        ]" :title="$t('Достижения')">


        <div class="block">
            <p class="info">{{ $t('info') }}</p>

            <Link :href="route('lk.achievement.create')" class="btn btn-s btn-primary">
                {{ $t('Добавить') }}
            </Link>
            <br><br>
        </div>

        <div class="item-card--group">
            <div v-for="item of items" class="block item-card clickable" @click="itemClick(item)">
                <div class="item-card--options">
                    <a class="fa fa-times btn-remove" @click.stop="remove(item)"></a>
                </div>
                <h2>{{ item.type.name }}</h2>
                <p class="item-card--property" v-if="item.name"><label>Наименование:</label> <span
                    class="value">{{ item.name }}</span></p>
            </div>
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
            this.$inertia.visit(route('lk.achievement.edit', {achievement: item.id}))
        },
        async remove(item) {
            let index = this.items.findIndex(itm => itm.id === item.id);
            let result = await axios.delete(route('lk.achievement.destroy', {achievement: item.id}));
            if (result.data.result == 'ok') {
                this.items.splice(index, 1);
                this.recalcEduLevels();
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        }
    }
}

</script>

<style lang="scss" scoped>
@import "resources/js/Pages/Lk/styles";
</style>

<i18n src="/resources/js/Pages/Lk/translations.json"/>
<i18n>
{
    "ru": {
        "info": "Достижения являются общими для всех выбранных вами профилей. Добавление достижений не является обязательным для подачи портфолио.",
        "Добавить": "Добавить"
    },
    "en": {
        "info": "Achievements are common to all the profiles you have selected. Adding achievements is not mandatory for submitting a portfolio.",
        "Добавить": "Add"
    }
}
</i18n>
