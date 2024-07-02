<template>

    <input type="number" class="input"
           v-model="value"
           :min="question.options.min"
           :max="question.options.max"
           :step="question.options.step"
            placeholder="Введите ваш ответ">

    <input-error :errors="errors" for="solution.**"/>

</template>

<script>

import InputError from "@/Components/InputError.vue";
import _isArray from "lodash/isArray";
import Checkbox from "@/Components/Checkbox.vue";

export default {
    name: "quiz-solve-number",
    components: {InputError},
    props: {
        errors: {
            default: null,
            type: Object
        },
        question: {
            default: null,
            type: Object
        },
        valid: {
            default: false, type: Boolean
        },
        solution: {
            default: null,
            type: Object
        },
    },
    emits: ['update:solution', 'update:valid'],
    computed: {
        value: {
            get() {
                if (this.solution == null
                    || !this.solution.hasOwnProperty('value')) {
                    return null;
                } else {
                    return this.solution.value;
                }
            },
            set(value) {
                if(value == null || value == ''){
                    this.$emit('update:solution', null);
                    this.$emit('update:valid', false);
                } else {
                    this.$emit('update:solution', {value: value});
                    this.$emit('update:valid', true);
                }
            }
        }
    },
    created() {
        this.$emit('update:valid', this.value != null);
    }
}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";
.input{max-width: 250px; margin: 10px 0 }
</style>

