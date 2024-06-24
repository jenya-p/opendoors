<template>
    <h2>Варианты</h2>
    <draggable
        v-if="lOptions.length"
        v-model="lOptions"
        handle=".input-label"
        group="group"
        item-key="index"
        ghost-class="ghost"
        animation="200"
    >
        <template #item="{element, index}">
            <div class="option" v-field-container>
                <field :errors="errors"
                       label="Русский текст"
                       :for="'options.options.' + element.index + '.text'" class="field-option">
                    <ckeditor v-model="element.text" :editor="editor" ref="optionTextInput" :config="{
                            width: '100%'
                        }"/>
                </field>
                <field :errors="errors"
                       label="Английский текст"
                       :for="'options.options.' + element.index + '.text_en'" class="field-option">
                    <ckeditor v-model="element.text_en" :editor="editor" :config="{
                            width: '100%'
                        }"/>
                </field>
                <field label="" class="field-right">
                    <checkbox v-model="right" :value="element.index">Верный ответ</checkbox>
                    <a class="btn-remove" title="Удалить вариант" @click="removeOption(index)">Удалить <i
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
    <br><br>
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
    <input-error :errors="errors" for="verification.weightsTable.*,verification.weightsTable.*.*"/>
</template>

<script>

import Radio from "@/Components/Radio.vue";
import draggable from "vuedraggable";
import Field from "@/Components/Field.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import InputError from "@/Components/InputError.vue";
import CKEditor from "@ckeditor/ckeditor5-vue";
import Editor from '@ckeditor/ckeditor5-build-inline';
import _isArray from "lodash/isArray";
import Checkbox from "@/Components/Checkbox.vue";
import _isObject from "lodash/isObject";
import _isNumber from "lodash/isNumber";

let _counter = 0;

export default {
    name: "quiz-edit-multi",
    components: {Checkbox, InputError, TextareaAutosize, Field, draggable, Radio, ckeditor: CKEditor.component},
    props: {
        errors: {
            default: null,
            type: Object
        },
        options: {},
        verification: {},
    },
    emits: ['update:options', 'update:verification',],

    data() {
        return {
            lOptions: [],
            right: [],
            weights: [],
            editor: Editor,
            auto: true,
        }
    },
    methods: {
        removeOption(index) {
            if (this.right == this.lOptions[index].index) {
                this.right = null;
            }
            this.lOptions.splice(index, 1);
        },
        addOption() {
            this.lOptions.push({
                index: _counter++,
                text: '',
                text_en: ''
            });
            let $v = this;
            setTimeout(function () {
                $v.$refs.optionTextInput.$el.ckeditorInstance.editing.view.focus();
            }, 100);
        },

        update(updateWeights = false) {
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

            for (let r = 0; r <= correct.length; r++) {
                if(r >= this.weights.length) {
                    this.weights[r] = [];
                }
                weights[r] = [];
                for (let w = 0; w <= options.length - correct.length; w++) {
                    if(w >= this.weights[r].length) {
                        this.weights[r][w] = this.auto ? Math.max(0, r - w) : 0;
                    } else {
                        if(this.weights[r][w] != Math.max(0, r - w)){
                            this.auto = false;
                        }
                    }
                    weights[r][w] = this.weights[r][w];
                }
            }
            this.$emit('update:options', {options: options});
            this.$emit('update:verification', {correct: correct, weightsTable: weights});
        }
    },
    watch: {
        lOptions: {
            deep: true,
            handler(n, o) {
                this.update();
            }
        },
        right(n, o) {
            this.update();
        },
        weights: {
            deep: true,
            handler(n, o){
                this.update();
            }
        },
        auto(value){
            if(value){
                this.weights = [];
                this.update();
            }
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
                    weights[r][w] = this.auto ? Math.max(0, r - w) : 0;
                } else {
                    if(weights[r][w] != Math.max(0, r - w)){
                        this.auto = false;
                    }
                }
            }
        }
        this.weights = weights;
    }
}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";

:deep(.option) {
    border-radius: 5px;
    padding-left: 10px;
    position: relative;
    border-left: 2px solid $shadow-color;

    .btn-remove {
        color: $dark-shadow-color;
        font-size: 0.9em;

        &:hover {
            color: $attractive-color;
        }

        padding: 0;
    }

    .handler {
        color: $dark-shadow-color;
        border-radius: 5px;
        font-size: 1.4em;
        padding: 0.5em 0.5em 0.5em 0.5em;
        cursor: grab;
        position: absolute;
        left: 0px;
        top: 0px;

        &:active {
            cursor: grabbing;
        }
    }

    &:not(.vertical) .field .input-label {
        width: 230px;
    }

    .input-label {
        cursor: grab
    }

    .field-right .field-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

}

.weight-table {
    td, th {
        width: 100px;
        text-align: center;
        border: 1px solid $shadow-color;
        vertical-align: middle
    }

    td {
    }

    th {
        font-weight: bold;
        padding: 5px 0;
    }

    input {
        margin: 0px;
        border-radius: 0;
        padding: 0 5px 0 20px;
        text-align: center;

        &:not(:focus) {
            border-color: transparent
        }
    }
}

</style>
<style lang="scss">
.ghost {
    border-color: #ccc;
    opacity: 0.5;
}
</style>
