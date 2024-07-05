<template>

    <textarea-autosize class="input" rows="5"
           v-model="value" placeholder="Введите ваш ответ" />

    <Attachments :items="attachments" :item_id="$page.props.auth.user.id" item_type="question_probe"/>

    <input-error :errors="errors" for="solution.**"/>

</template>

<script>

import InputError from "@/Components/InputError.vue";
import _isArray from "lodash/isArray";
import Checkbox from "@/Components/Checkbox.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import Attachments from "@/Components/Attachments.vue";

export default {
    name: "quiz-solve-free",
    components: {Attachments, TextareaAutosize, InputError},
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
        }
    },
    data(){
        return {
            attachments: []
        }
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
.input{margin: 10px 0 }
</style>

