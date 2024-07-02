<template>
    <h2>Варианты ответов</h2>
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
                       :for="'options.' + element.index + '.text'" class="field-option">
                    <ckeditor v-model="element.text" :editor="editor" ref="optionTextInput" :config="{
                            width: '100%'
                        }"/>
                </field>
                <field :errors="errors"
                       label="Английский текст"
                       :for="'options.' + element.index + '.text_en'" class="field-option">
                    <ckeditor v-model="element.text_en" :editor="editor" :config="{
                            width: '100%'
                        }"/>
                </field>
                <field label="" class="field-right">
                    <radio v-model="right" :value="element.index">Верный ответ</radio>
                    <a class="btn-remove" title="Удалить вариант" @click="removeOption(index)">Удалить <i
                        class="fa fa-times"></i></a>
                </field>
            </div>
        </template>
    </draggable>
    <a class="btn btn-primary btn-xs add-option" @click="addOption">Добавить</a>
    <input-error :errors="errors" for="options,verification,verification.*,verification.*.*"/>

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
import _isObject from "lodash/isObject.js";
import _isNumber from "lodash/isNumber";

let _counter = 0;

export default {
    name: "quiz-edit-one",
    components: {InputError, TextareaAutosize, Field, draggable, Radio, ckeditor: CKEditor.component},
    props: {
        errors: {
            default: null,
            type: Object
        },
        options: {},
        verification: {},
    },
    emits: ['update:options', 'update:verification',],

    data(){
        return {
            lOptions: [],
            right: null,
            editor: Editor
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
            setTimeout(function(){
                $v.$refs.optionTextInput.$el.ckeditorInstance.editing.view.focus();
            }, 100);
        },

        update(){
            let correct = this.lOptions.findIndex(itm => itm.index == this.right);
            if (correct == -1) {
                correct = null;
            }

            let options = null;
            if(this.lOptions.length){
                options = this.lOptions.map(function(itm){ return {
                    text: itm.text, text_en: itm.text_en
                };});
            }
            this.$emit('update:options', {options: options});
            this.$emit('update:verification', {correct: correct});
        }

    },
    watch: {
        lOptions: {
            deep: true,
            handler(){
                this.update();
            }
        },
        right() {
            this.update();
        }
    },
    created() {
        _counter = 0;
        if(_isObject(this.options) && _isArray(this.options.options)){
            this.lOptions = this.options.options.map(function(itm){
                itm.index = _counter++;
                return itm;
            });
        } else {
            this.lOptions = [];
        }
        this.right = null;
        if (_isObject(this.verification) &&
            this.verification.hasOwnProperty('correct')) {
            if (_isArray(this.verification.correct) && this.verification.correct.length > 0) {
                this.right = Math.min.apply(null, this.verification.correct);
            } else if (_isNumber(this.verification.correct)) {
                this.right = this.verification.correct;
            }
        }
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

</style>
<style lang="scss">
.ghost {
    border-color: #ccc;
    opacity: 0.5;
}
</style>
