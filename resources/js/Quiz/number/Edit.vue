<template>
    <h2>Настройки ответа</h2>
<!--
    <field :errors="errors" for="options.min" label="Минимальное значение" class="field-short">
        <input type="number" class="input" v-model="lOptions.min">
    </field>

    <field :errors="errors" for="options.max" label="Максимальное значение" class="field-short">
        <input type="number" class="input" v-model="lOptions.max">
    </field>

    <field :errors="errors" for="options.step" label="Шаг ввода" class="field-short">
        <input type="number" class="input" v-model="lOptions.step" step="any">
    </field>
-->

    <field :errors="errors" for="options.type" class="field-radios">
        <radio v-model="lOptions.type" value="single">Число</radio>
        <radio v-model="lOptions.type" value="range">Числовой диапазон</radio>
    </field>
    <field :errors="errors" for="verification,verification.*" label="Диапазон верного ответа"  v-if="lOptions.type=='range'">
        <div class="input-group">
            <input type="number" class="input" v-model="lVerification[0]" placeholder="От" step="0.001"><span> - </span>
            <input type="number" class="input" v-model="lVerification[1]" placeholder="До" step="0.001">
        </div>
    </field>
    <field :errors="errors" for="verification" label="Верный ответ" class="field-short" v-else>
        <input type="number" class="input" v-model="lVerification" step="0.001">
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
    name: "quiz-edit-number",
    components: {Radio, InputError, Field, draggable},
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
            lOptions: {min: null, max: null, step: null, type: 'single'},
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
        'lOptions.type'(v){
            if(v == 'range'){
                if(!_isArray(this.lVerification)) {
                    this.lVerification = [this.lVerification, null];
                }
            } else {
                if(_isArray(this.lVerification)) {
                    this.lVerification = this.lVerification[0];
                }
            }
        },
        lOptions: {
            deep: true,
            handler() {
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
        this.lOptions = _extend({min: null, max: null, step: null, type: 'single'}, this.options);
        if (_isNumber(this.verification)) {
            if(this.lOptions.type == 'range'){
                this.lVerification = [this.verification,this.verification];
            } else {
                this.lVerification = this.verification;
            }
        } else if (_isArray(this.verification)) {
            if(this.lOptions.type == 'range'){
                this.lVerification = this.verification;
            } else {
                this.lVerification = this.verification[0];
            }
        } else {
            if(this.lOptions.type == 'range'){
                this.lVerification = [null,null];
            } else {
                this.lVerification = null;
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";
.input-group{display: flex;
    span{line-height: 42px; margin: 0 15px}
    input {width: 250px}
}
</style>
