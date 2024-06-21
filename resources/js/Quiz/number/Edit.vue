<template>
    <h2>Настройки ответа</h2>

    <field :errors="errors" for="options.min" label="Минимальное значение" class="field-short">
        <input type="number" class="input" v-model="lOptions.min">
    </field>

    <field :errors="errors" for="options.max" label="Максимальное значение" class="field-short">
        <input type="number" class="input" v-model="lOptions.max">
    </field>

    <field :errors="errors" for="options.step" label="Шаг ввода" class="field-short">
        <input type="number" class="input" v-model="lOptions.step">
    </field>

    <field :errors="errors" for="verification" label="Верный ответ" class="field-short">
        <input type="number" class="input" v-model="lVerification">
    </field>

</template>

<script>

import draggable from "vuedraggable";
import Field from "@/Components/Field.vue";
import InputError from "@/Components/InputError.vue";
import _extend from "lodash/extend";
import _isNumber from "lodash/isNumber";


export default {
    name: "quiz-edit-one",
    components: {InputError, Field, draggable},
    props: {
        errors: {
            default: null,
            type: Object
        },
        options: {
            type: Object
        },
        verification: {
            type: Object
        },
    },
    emits: ['update:options', 'update:verification',],

    data() {
        return {
            lOptions: {min: null, max: null, step: null},
            lVerification: null,
        }
    },
    methods: {

        update() {
            this.$emit('update:options', this.lOptions);
            this.$emit('update:verification', this.lVerification);
        }

    },
    watch: {
        lOptions: {
            deep: true,
            handler() {
                this.update();
            }
        },
        lVerification() {
            this.update();
        }
    },
    created() {
        this.lOptions = _extend({min: null, max: null, step: null}, this.options);
        if (_isNumber(this.verification)) {
            this.lVerification = this.verification;
        } else {
            this.lVerification = null;
        }
    }
}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";

</style>
