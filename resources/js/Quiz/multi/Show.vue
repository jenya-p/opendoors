<template>
    <h2>Варианты ответов</h2>
    <div><div v-for="(element, index) of options.options" class="option display" v-field-container>
        <span class="option-counter">{{ index + 1 }}</span>
        <field label="Русский текст" class="field-option field-display">
            <div v-html="element.text"></div>
        </field>
        <field label="Английский текст" class="field-option field-display">
            <div v-html="element.text_en"></div>
        </field>
        <field label="Верный ответ" class="field-right field-display">
            {{verification.correct.includes(index) ? 'Да':'Нет'}}
        </field>
    </div>
    </div>
    <h2 style="margin-top: 2em">Распределение баллов</h2>

    <table class="weight-table">
        <tr>
            <th rowspan="2" style="font-size: 0.8em">Правильные<br/>ответы</th>
            <th :colspan="options.options.length - verification.correct.length  + 1" style="font-size: 0.8em">Неправильные
                ответы
            </th>
        </tr>
        <tr>
            <th v-for="w in options.options.length - verification.correct.length  + 1" style="background-color: #ffecec">
                {{ w - 1 }}
            </th>
        </tr>
        <tr v-for="r in verification.correct.length + 1">
            <th style="background-color: #ecffed">{{ r - 1 }}</th>
            <td v-for="w in options.options.length - verification.correct.length + 1">
                {{ verification.weightsTable[r - 1][w - 1] }}
            </td>
        </tr>
    </table>

</template>

<script>

import draggable from "vuedraggable";
import Field from "@/Components/Field.vue";

let _counter = 0;

export default {
    name: "quiz-show-multi",
    components: {Field, draggable},
    props: {
        options: {},
        verification: {},
        maxWeight: 0,
    },

    data() {
        return {
            right: [],
        }
    },
    methods: {

    },
    created() {
    }
}
</script>

<style lang="scss" scoped src="./styles.scss">
<style lang="scss">
.ghost {
    border-color: #ccc;
    opacity: 0.5;
}
</style>
