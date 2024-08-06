<template>
    <AdminLayout :breadcrumb="[
        {link: route('lk.dashboard'), label: $t('Личный кабинет')},
        {link: route('lk.degree.index'), label: $t('Образование')},
        item.id ? item.edu_level.name : $t('Сведения о полученном образовании')
        ]" :title="$t('Образование')">
        <form method="post" @submit.prevent="submit" class="block lk-form" v-field-container>
            <h2>{{ item.edu_level.name }}</h2>

            <field :errors="form.errors" for="country_id" label="Страна">
                <vue-multiselect :options="country_options" v-model="country" track-by="id" label="name"/>
            </field>

            <field :errors="form.errors" for="name" label="Полное наименование учебного заведения" description="Латиницей, как указано на сайте / в документах учебного заведения">
                <textarea-autosize v-model="form.name" class="input" rows="1"/>
            </field>

            <field :errors="form.errors" for="graduation_year" label="Год окончания">
                <vue-multiselect :options="year_options" v-model="year" track-by="id" label="name"/>
            </field>

            <field :errors="form.errors" for="diploma_title" label="Тема диплома" v-if="item.edu_level.diploma"  description="Полная тема дипломной научной работы, как в подтверждающем документе">
                <textarea-autosize v-model="form.diploma_title" class="input" rows="1"/>
            </field>

            <field :errors="form.errors" for="is_study_russian,is_study_english" label="Проходил(а) обучение" class="field-checkboxes">
                <checkbox v-model="form.is_study_russian" >на русском</checkbox>
                <checkbox v-model="form.is_study_english" >на английском</checkbox>
            </field>

            <div class="attachment-field">
                <h2>{{$t('ATTACHMENT_LABEL')}}</h2>
                <p class="input-description">{{$t('ATTACHMENT_DESC')}}</p>
                <input-error :errors="form.errors" for="attachments.**"/>
                <Attachments :items="form.attachments" item_type="degree" :item_id="item.id"/>

            </div>

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

export default {
    components: {InputError, TextareaAutosize, Attachments, Checkbox, Field, TableBottom, AdminLayout, VueMultiselect},
    props: {
        country_options: Array,
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        let yearOptions = [];
        for(let y = 2025; y > 1950; y--){
            yearOptions.push({id: y, name: y});
        }
        return {
            year_options: yearOptions,
            form: useForm(_extend({
                edu_level_id: null,
                country_id: null,
                name: '',
                graduation_year: null,
                diploma_title: '',
                is_study_russian: false,
                is_study_english: false,
                attachments: []
            }, this.item))
        }
    },

    methods: {
        submit() {
            if (this.item.id) {
                this.form.put(route('lk.degree.update', {degree: this.item.id}));
            } else {
                this.form.post(route('lk.degree.store'));
            }
        },
        close(highlight = false){
            router.visit(route('lk.degree.index'));
        }
    },

    computed:{
        year: selectable('year_options', 'form', 'graduation_year'),
        country: selectable('country_options', 'form', 'country_id'),
    }


}

</script>
<style lang="scss" scoped>
:deep(.attachment-field){
    margin-bottom: 4em;
    .input-error{
        margin: -7px 0 15px;
    }
}
</style>
<i18n src="@/Pages/Lk/translations.json" />
<i18n>
{
    "ru": {
        "ATTACHMENT_DESC": "Все документы необходимо предоставлять на русском или английском языке. Документы на других языках не рассматриваются. Если оригинал вашего документа об образовании на другом языке, требуется перевод.",
        "ATTACHMENT_LABEL": "Копия документа об образовании (если нет, справка об успеваемости)"
    },
    "en": {
        "ATTACHMENT_LABEL":  "A copy of the education document (if not, a certificate of academic performance)",
        "ATTACHMENT_DESC":  "All documents must be submitted in Russian or English. Documents in other languages are not considered. If the original of your education document is in another language, a translation is required."
    }
}
</i18n>
