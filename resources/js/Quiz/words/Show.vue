<template>
    <h2>Эталонный ответ</h2>

    <field label="Эталон" class="field-display">
        <div v-html="verification.pattern" />
    </field>

    <field label="Эталон (Англ.)" class="field-display">
        <div v-html="verification.pattern_en" />
    </field>

</template>

<script>

import Field from "@/Components/Field.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import InputError from "@/Components/InputError.vue";
import _extend from "lodash/extend";

export default {
    name: "quiz-show-words",
    components: {Field},
    props: {

        options: {
            type: Object
        },
        verification: {
            type: Object
        },
    }
}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";

</style>
