<template>
    <h2>Настройки ответа</h2>

    <field :errors="errors" for="options.max" label="Максимальная длина ответа" class="field-short" description="Символов">
        <input type="number"  class="input" v-model="lOptions.max">
    </field>

    <field :errors="errors" for="verification.guide" label="Методические указания"
        description="Инструкция по проверке этого задания">
        <textarea-autosize v-model="lVerification.guide" class="input"></textarea-autosize>
    </field>

</template>

<script>

import Field from "@/Components/Field.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import InputError from "@/Components/InputError.vue";
import _extend from "lodash/extend";
export default {
    name: "quiz-edit-free",
    components: {InputError, TextareaAutosize, Field},
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

    data(){
        return {
            lOptions: {max: null},
            lVerification: {guide: null},
        }
    },
    methods: {

        update(){
            this.$emit('update:options', this.lOptions);
            this.$emit('update:verification', this.lVerification);
        }

    },
    watch: {
        lOptions: {
            deep: true,
            handler(){
                this.update();
            }
        },
        lVerification: {
            deep: true,
            handler() {
                this.update();
            }
        }
    },
    created() {
        this.lOptions = _extend({max: null}, this.options);
        this.lVerification = _extend({guide: null}, this.verification);
    }
}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";

</style>
