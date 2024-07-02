<template>

    <ul>
        <li v-for="(option, index) of question.options.options">
            <div class="content" v-html="option.text" style="margin-top: 2.5em; margin-bottom: 1em"></div>
            <vue-multiselect
                :options="question.options.categories" :multiple="question.options.multiple" :allow-empty="question.options.allowEmpty"
                track-by="index" label="text"
                v-model="choices[index]"
            />
        </li>
    </ul>

    <input-error :errors="errors" for="solution.**"/>

</template>

<script>

import InputError from "@/Components/InputError.vue";
import VueMultiselect from "vue-multiselect";

export default {
    name: "quiz-solve-match",
    components: {InputError,
        VueMultiselect},
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
    data(){
        let choices = [];
        for (const oIndex of this.question.options.options) {
            let c = [];
            choices.push(c)
        }
        return {
            choices: choices
        };
    },
    computed: {

    },
    created() {
        this.$emit('update:valid', true);
    }
}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";

li {
    margin-bottom: 3.2em
}
</style>

