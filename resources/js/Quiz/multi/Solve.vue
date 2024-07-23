<template>

    <ul>
        <li v-for="(options, index) of question.options.options">
            <checkbox v-model="choices" :value="index">
                <div class="ck-content" v-html="options.text" style="margin-top: 1.5px"></div>
            </checkbox>
        </li>
    </ul>

    <input-error :errors="errors" for="solution.**"/>

</template>

<script>

import InputError from "@/Components/InputError.vue";
import _isArray from "lodash/isArray";
import Checkbox from "@/Components/Checkbox.vue";

export default {
    name: "quiz-solve-multi",
    components: {Checkbox, InputError},
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
        choices: {
            get() {
                if (this.solution == null
                    || !this.solution.hasOwnProperty('choices')
                    || !_isArray(this.solution.choices)) {
                    return [];
                } else {
                    return this.solution.choices;
                }
            },
            set(value) {
                this.$emit('update:solution', {choices: value});
                this.$emit('update:valid', true);
            }
        }
    },
    created() {
        this.$emit('update:valid', true);
    }
}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";

li {
    margin-bottom: 1.2em
}
</style>

