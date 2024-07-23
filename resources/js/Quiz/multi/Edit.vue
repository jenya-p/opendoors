<template>
    <h2>Варианты ответов</h2>
    <draggable
        v-if="lOptions.length"
        v-model="lOptions"
        handle=".option-counter"
        group="group"
        item-key="index"
        ghost-class="ghost"
        animation="200"
    >
        <template #item="{element, index}">
            <div class="option" v-field-container>
                <span class="option-counter">{{ index + 1 }}</span>
                <field :errors="errors"
                       label="Русский текст"
                       :for="'options.options.' + element.index + '.text'" class="field-option">
                    <editor v-model="element.text" ref="optionTextInput" :item="['question', $parent.item?.id, 'option']"/>
                </field>
                <field :errors="errors"
                       label="Английский текст"
                       :for="'options.options.' + element.index + '.text_en'" class="field-option">
                    <editor v-model="element.text_en" :item="['question', $parent.item?.id, 'option_en']"/>
                </field>
                <field label="" class="field-right">
                    <checkbox v-model="right" :value="element.index">Верный ответ</checkbox>
                    <a class="btn-remove" title="Удалить вариант" @click="removeOption(element.index)">Удалить <i
                        class="fa fa-times"></i></a>
                </field>

            </div>
        </template>
    </draggable>
    <a class="btn btn-primary btn-xs add-option" @click="addOption">Добавить</a>
    <input-error :errors="errors" for="verification.correct,verification.correct.*"/>

    <h2 style="margin-top: 2em">Распределение баллов</h2>
    <br/>
    <checkbox v-model="auto">Заполнить автоматически</checkbox>

    <field :errors="errors" for="verification.weightsTable.**" >
        <table class="weight-table">
            <tr>
                <th rowspan="2" style="font-size: 0.8em">Правильные<br/>ответы</th>
                <th :colspan="this.lOptions.length - this.right.length  + 1" style="font-size: 0.8em">Неправильные ответы
                </th>
            </tr>
            <tr>
                <th v-for="w in this.lOptions.length - this.right.length  + 1" style="background-color: #ffecec">
                    {{ w - 1 }}
                </th>
            </tr>
            <tr v-for="r in this.right.length + 1">
                <th style="background-color: #ecffed">{{ r - 1 }}</th>
                <td v-for="w in this.lOptions.length - this.right.length + 1">
                    <input type="number" v-model="weights[r - 1][w - 1]" class="input" style="width: 100px">
                </td>
            </tr>
        </table>
    </field>

</template>

<script>

import Radio from "@/Components/Radio.vue";
import draggable from "vuedraggable";
import Field from "@/Components/Field.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import InputError from "@/Components/InputError.vue";
import Editor from "@/Components/Editor.vue";
import _isArray from "lodash/isArray";
import Checkbox from "@/Components/Checkbox.vue";
import _isObject from "lodash/isObject";
import _isNumber from "lodash/isNumber";

let _counter = 0;

export default {
    name: "quiz-edit-multi",
    components: {Checkbox, InputError, TextareaAutosize, Field, draggable, Radio, Editor},
    props: {
        errors: {
            default: null,
            type: Object
        },
        options: {},
        verification: {},
        maxWeight: 0,
    },
    emits: ['update:options', 'update:verification',],

    data() {
        return {
            lOptions: [],
            right: [],
            weights: [],
            auto: true,
        }
    },
    methods: {
        removeOption(index) {
            let rIndex = this.right.indexOf(index);
            if(rIndex !== -1){
                this.right.splice(rIndex, 1);
            }
            let oIndex = this.lOptions.findIndex(itm => itm.index === index);
            if(oIndex !== -1){
                this.lOptions.splice(oIndex, 1);
            }
        },
        addOption() {
            this.lOptions.push({
                index: _counter++,
                text: '',
                text_en: ''
            });
            let $v = this;
            setTimeout(function () {
                $v.$refs.optionTextInput.focus();
            }, 100);
        },

        update: function(force) {
            let correct = [];
            let options = [];
            let weights = [];

            for (const index in this.lOptions) {
                if (this.right.indexOf(this.lOptions[index].index) !== -1) {
                    correct.push(parseInt(index));
                }
            }

            if (this.lOptions.length) {
                options = this.lOptions.map(function (itm) {
                    return {
                        text: itm.text, text_en: itm.text_en
                    };
                });
            }

            if(force){
                this.weights = [];
            }
            for (let r = 0; r <= correct.length; r++) {
                if(r >= this.weights.length) {
                    this.weights[r] = [];
                }
                weights[r] = [];
                for (let w = 0; w <= options.length - correct.length; w++) {
                    if(w >= this.weights[r].length) {
                        this.weights[r][w] = this.auto ? this.getAutoWeight(r,w) : 0;
                    } else {
                        if(this.weights[r][w] != this.getAutoWeight(r,w)){
                            this.auto = false;
                        }
                    }
                    weights[r][w] = this.weights[r][w];
                }
            }

            this.$emit('update:options', {options: options});
            this.$emit('update:verification', {correct: correct, weightsTable: weights});
        },
        getAutoWeight(r, w){

            let mw = 1;

            if(this.maxWeight && this.right.length != 0){
                mw = this.maxWeight / this.right.length;
            }

            return Math.round(Math.max(0, (r - w) * mw));
        }
    },
    watch: {
        lOptions: {
            deep: true,
            handler(n, o) {
                this.update(this.auto);
            }
        },
        right(n, o) {
            this.update(this.auto);
        },
        weights: {
            deep: true,
            handler(n, o){
                this.update(false);
            }
        },
        auto(value){
            if(value){
                this.update(true);
            }
        },
        maxWeight(value){
            this.update(this.auto);
        }
    },
    created() {
        _counter = 0;
        if (_isObject(this.options) && _isArray(this.options.options)) {
            this.lOptions = this.options.options.map(function (itm) {
                itm.index = _counter++;
                return itm;
            });
        } else {
            this.lOptions = [];
        }

        this.right = [];
        if (_isObject(this.verification) &&
            this.verification.hasOwnProperty('correct')) {
            if (_isArray(this.verification.correct)) {
                this.right = this.verification.correct;
            } else if (_isNumber(this.verification)) {
                this.right = [this.verification.correct];
            }
        }


        let weights = this.verification.weightsTable;
        if (!_isArray(weights)) {
            weights = [];
        }
        for (let r = 0 ; r < this.right.length + 1; r++) {
            if(r >= weights.length){
                weights[r] = [];
            }
            for (let w = 0; w < this.lOptions.length - this.right.length + 1; w++) {
                if(w >= weights[r].length){
                    weights[r][w] = this.auto ? this.getAutoWeight(r,w) : 0;
                } else {
                    if(weights[r][w] != this.getAutoWeight(r,w)){
                        this.auto = false;
                    }
                }
            }
        }
        this.weights = weights;
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
