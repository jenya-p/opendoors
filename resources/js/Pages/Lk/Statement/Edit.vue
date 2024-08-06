<template>
    <AdminLayout :breadcrumb="[
        {link: route('lk.dashboard'), label: $t('Личный кабинет')},
        {link: route('lk.statement.index'), label: $t('Мотивационное письмо')},
        item.id ? item.member.profile.name : $t('Новое мотивационное письмо')
        ]" :title="$t('Мотивационное письмо')">

        <form method="post" @submit.prevent="submit" class="block lk-form" v-field-container>

            <h2>Научные интересы</h2>
            <p class="info">Выберите области знания, соответствующие вашим научным интересам в рамках выбранного профиля. Вы можете выбрать от 1 до 3 блоков областей знания. Для каждого из них — область знания и научное направление.</p>
            <div v-for="(area, index) of form.area_ids">
                  <KnowledgeArea :knowledge_tree="knowledge_tree" v-model="form.area_ids[index]" @remove="removeArea(index)"/>
                  <input-error :errors="form.errors" :for="'area_ids.' + index" />
            </div>
            <a class="btn btn-primary btn-xs" @click.stop="addArea()" style="width: auto" v-if="form.area_ids.length < 3">Добавить блок областей знания</a>
            <input-error :errors="form.errors" for="area_ids" />

            <br><br>

            <field :errors="form.errors" for="interests" label="Описание научных интересов">
                <textarea-autosize v-model="form.interests" class="input" rows="2"/>
            </field>

            <field :errors="form.errors" for="goals" label="Личные цели">
                <textarea-autosize v-model="form.goals" class="input" rows="2"/>
            </field>

            <field :errors="form.errors" for="achievements" label="Профессиональные достижения">
                <textarea-autosize v-model="form.achievements" class="input" rows="2"/>
            </field>

            <field :errors="form.errors" for="motive" label="Причина выбора профиля">
                <textarea-autosize v-model="form.motive" class="input" rows="2"/>
            </field>

            <field :errors="form.errors" for="country" label="Причина выбора обучения в России">
                <textarea-autosize v-model="form.country" class="input" rows="2"/>
            </field>

            <br><br><br>

            <table-bottom align="left">
                <a @click="close()" class="btn btn-primary btn-editor-close fa fa-chevron-left" title="Закрыть"/>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </table-bottom>

        </form>
    </AdminLayout>
</template>
<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TableBottom from "@/Components/TableBottom.vue";
import {router, useForm} from "@inertiajs/vue3";
import {closeEditor, selectable} from "@/Components/utils.js";
import Field from "@/Components/Field.vue";
import VueMultiselect from "vue-multiselect";
import Checkbox from "@/Components/Checkbox.vue";
import Attachments from "@/Components/Attachments.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import InputError from "@/Components/InputError.vue";
import _extend from "lodash/extend";
import KnowledgeArea from "@/Pages/Lk/Degree/KnowledgeArea.vue";

export default {
    components: {
        KnowledgeArea,
        InputError, TextareaAutosize, Attachments, Checkbox, Field, TableBottom, AdminLayout, VueMultiselect},
    props: {
        knowledge_tree: {},
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            form: useForm(_extend({
                member_id: null,
                area_ids: [],
                interests: '',
                goals: '',
                achievements: '',
                motive: '',
                country: '',
            }, this.item))
        }
    },

    methods: {
        removeArea(index){
            this.form.area_ids.splice(index,1);
        },
        addArea(){
            this.form.area_ids.push(null);
        },

        submit() {
            if (this.item.id) {
                this.form.put(route('lk.statement.update', {statement: this.item.id}));
            } else {
                this.form.post(route('lk.statement.store'));
            }
        },
        close(highlight = false) {
            router.visit(route('lk.statement.index'));
        }
    },

    computed: {

    }


}

</script>
<style lang="scss" scoped>

</style>
<i18n src="@/Pages/Lk/translations.json"/>
<i18n>
{
    "ru": {


    },
    "en": {


    }
}
</i18n>
