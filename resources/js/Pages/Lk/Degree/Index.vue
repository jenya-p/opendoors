<template>
    <AdminLayout :breadcrumb="[
        {link: route('lk.dashboard'), label: $t('Личный кабинет')},
        $t('Образование')
        ]" :title="$t('Образование')">


        <div class="block">
            <h2>{{ $t('Добавить информацию об образовании') }}</h2>
            <p class="info">{{ $t('info') }}</p>

            <table class="items-table">
                <tr v-for="level of participant.edu_levels" :class="{disabled: level.disabled}">
                    <td class="label">
                        {{ level.name }}
                    </td>
                    <td class="button">
                        <Link :href="route('lk.degree.create', {edu_level_id: level.id})" class="btn btn-s btn-primary"
                            v-if="!level.disabled">
                            {{ $t('Добавить') }}
                        </Link>
                        <a v-else class="btn btn-s btn-primary disabled" >{{ $t('Добавить') }}</a>
                    </td>
                </tr>
            </table>
        </div>

        <div class="item-card--group">
            <div v-for="item of items" class="block item-card clickable" @click="itemClick(item)">
                <div class="item-card--options">
                    <a class="fa fa-times btn-remove" @click.stop="remove(item)"></a>
                </div>
                <h2>{{ item.edu_level.name }}</h2>
                <p class="item-card--property"><label>Название учебного заведения:</label> <span
                    class="value">{{ item.name }}</span></p>
                <p class="item-card--property"><label>Год окончания:</label> <span class="value">{{
                        item.graduation_year
                    }}</span></p>
                <p class="item-card--property"><label>Страна, где находится учебное заведение:</label> <span
                    class="value">{{ item.country.name }}</span></p>
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
            this.$inertia.visit(route('lk.degree.edit', {degree: item.id}))
        },
        async remove(item) {
            let index = this.items.findIndex(itm => itm.id === item.id);
            let result = await axios.delete(route('lk.degree.destroy', {degree: item.id}));
            if (result.data.result == 'ok') {
                this.items.splice(index, 1);
                this.recalcEduLevels();
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        },
        recalcEduLevels(){
            for (const edu_level of this.participant.edu_levels) {
                edu_level.count = this.items.filter(itm => itm.edu_level_id == edu_level.id).length
                edu_level.disabled = (!edu_level.multiple && edu_level.count > 0) || this.items.length >= 4;
            }
        }
    },
    created() {
        this.recalcEduLevels();
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
        "info": "Вы можете добавить информацию максимум о четырёх полученных и/или получаемых на данный момент образованиях.",
        "Добавить": "Добавить"
    },
    "en": {
        "info": "You can add information about a maximum of four received and/or currently received formations.",
        "Добавить": "Add"
    }
}
</i18n>
