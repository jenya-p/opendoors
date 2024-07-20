<template>
    <h2>Настройки ответа</h2>

    <field label="Максимальная длина ответа" class="field-short" description="Символов">
        {{options.max}}
    </field>

    <field :errors="errors" for="options.uploads" class="field-checkboxes" label="">
        <span>Разрешить загрузку файлов участником:
            {{options.uploads ? 'Да' : 'Нет'}}
        </span>
    </field>

    <field :errors="errors" for="verification.guide" label="Методические указания"
        description="Инструкция по проверке этого задания">
        {{lVerification.guide}}
    </field>

</template>

<script>

import Field from "@/Components/Field.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import InputError from "@/Components/InputError.vue";
import _extend from "lodash/extend";
import Checkbox from "@/Components/Checkbox.vue";
export default {
    name: "quiz-show-free",
    components: {Field},
    props: {
        options: {
            type: Object
        },
        verification: {
            type: Object
        },
        maxWeight: 0,
    },

}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";

</style>
