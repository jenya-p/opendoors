<template>

    <ul>
        <li v-for="(options, index) of question.options.options">
            <radio v-model="choice" :value="index">
                <div class="content" v-html="options.text" style="margin-top: 1.5px"></div>
            </radio>
        </li>
    </ul>

    <input-error :errors="errors" for="solution.**"/>

</template>

<script>

import Radio from "@/Components/Radio.vue";
import InputError from "@/Components/InputError.vue";
import _isArray from "lodash/isArray";

export default {
    name: "quiz-solve-one",
    components: {InputError, Radio},
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
        choice: {
            get() {
                if (this.solution == null || !this.solution.hasOwnProperty('choice')) {
                    return null;
                } else {
                    return this.solution.choice;
                }
            },
            set(value) {
                if (value == null) {
                    this.$emit('update:solution', null);
                    this.$emit('update:valid', false);
                } else {
                    this.$emit('update:solution', {choice: value});
                    this.$emit('update:valid', true);
                }
            }
        }
    },
    created() {
        this.$emit('update:valid', this.choice != null);
    }
}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";

li {
    margin-bottom: 1.2em
}
</style>

