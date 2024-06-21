<template>
    <h2>Эталонный ответ</h2>

    <field :errors="errors" for="verification.pattern" label="Эталон"
           description="Каждое слово на новой строке">
        <textarea-autosize v-model="lVerification.pattern" class="input"/>
    </field>

    <field :errors="errors" for="verification.pattern_en" label="Эталон (Англ.)"
           description="Каждое слово на новой строке">
        <textarea-autosize v-model="lVerification.pattern_en" class="input"/>
    </field>

</template>

<script>

import Field from "@/Components/Field.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import InputError from "@/Components/InputError.vue";
import _extend from "lodash/extend";

export default {
    name: "quiz-edit-words",
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

    data() {
        return {
            // lOptions: [],
            lVerification: {pattern: null, pattern_en: null},
        }
    },
    methods: {

        update() {
            // this.$emit('update:options', this.lOptions);
            this.$emit('update:verification', this.lVerification);
        }

    },
    watch: {
        // lOptions: {
        //     deep: true,
        //     handler() {
        //         this.update();
        //     }
        // },
        lVerification: {
            deep: true,
            handler() {
                this.update();
            }
        }
    },
    created() {
        // this.lOptions = _extend({}, this.options);
        this.lVerification = _extend({pattern: null, pattern_en: null}, this.verification);
    }
}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";

</style>
