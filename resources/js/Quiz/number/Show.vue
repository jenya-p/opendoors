<template>
    <h2>Настройки ответа</h2>

    <field label="Тип" class="field-display">
        <span v-if="options.type == 'single'">Число</span>
        <span v-else-if="options.type == 'range'">Числовой диапазон</span>
    </field>
    <field label="Диапазон верного ответа" v-if="options.type=='range'" class="field-display">
        <div class="input-group">
            {{verification[0]}} -
            {{verification[1]}}
        </div>
    </field>
    <field  label="Верный ответ" v-else class="field-display">
        {{verification}}
    </field>
</template>

<script>

import draggable from "vuedraggable";
import Field from "@/Components/Field.vue";
import InputError from "@/Components/InputError.vue";
import _extend from "lodash/extend";
import _isNumber from "lodash/isNumber";
import Radio from "@/Components/Radio.vue";
import _isArray from "lodash/isArray";


export default {
    name: "quiz-show-number",
    components: {Field},
    props: {
        options: {
            type: Object
        },
        verification: {
            type: Object
        },
    },


}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";
.input-group{display: flex;
    span{line-height: 42px; margin: 0 15px}
    input {width: 250px}
}
</style>
