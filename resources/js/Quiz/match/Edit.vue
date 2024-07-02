<template>
    <h2>Варианты на сопоставление
        <span v-if="lCategories.length">({{ lCategories.length }})</span>
    </h2>

    <draggable
        v-if="lCategories.length"
        v-model="lCategories"
        handle=".option-counter"
        group="categories"
        item-key="index"
        ghost-class="ghost"
        animation="200"
    >
        <template #item="{element, index}">
            <div>
                <div class="option" v-field-container>
                    <span class="option-counter">{{ index + 1 }}</span>
                    <field :errors="errors"
                           label="Русский текст"
                           :for="'options.' + element.index + '.text'" class="field-option">
                        <input type="text" v-model="element.text" class="input" ref="categoryTextInput">
                    </field>
                    <field :errors="errors"
                           label="Английский текст"
                           :for="'options.' + element.index + '.text_en'" class="field-option">
                        <input type="text" v-model="element.text_en" class="input">
                    </field>
                    <field class="field-remove">
                        <a class="btn-remove" title="Удалить вариант" @click="removeCategory(index)">Удалить <i
                            class="fa fa-times"></i></a>
                    </field>
                </div>
            </div>
        </template>
    </draggable>
    <a class="btn btn-primary btn-xs add-option" @click="addCategory">Добавить вариант</a>
    <div style="height: 50px"></div>

    <h2>Категории <span v-if="lOptions.length">({{ lOptions.length }})</span></h2>
    <field label="Настройка категорий" class="field-checkboxes">
        <checkbox v-model="multiple">Разрешить множественный выбор</checkbox>
        <checkbox v-model="allowEmpty">Разрешить пустые ответы</checkbox>
    </field>

    <draggable
        v-if="lOptions.length"
        v-model="lOptions"
        handle=".option-counter"
        group="options"
        item-key="index"
        ghost-class="ghost"
        animation="200"
    >
        <template #item="{element, index}">
            <div><div class="option" v-field-container>
                <span class="option-counter">{{ $filters.ntl(index + 1) }}</span>
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
                <field :errors="errors"
                       label="Варианты"
                       :for="'matches.' + element.index" class="field-option">
                    <vue-multiselect :options="lCategories" v-model="element.matches"
                                     track-by="index" label="text"
                                     :multiple="true"
                                     :max="multiple ? null : 1"
                    />
                </field>
                <field class="field-remove">
                    <a class="btn-remove" title="Удалить вариант" @click="removeOption(index)">Удалить <i
                        class="fa fa-times"></i></a>
                </field>
            </div></div>
        </template>
    </draggable>
    <a class="btn btn-primary btn-xs add-option" @click="addOption">Добавить категорию</a>
    <input-error :errors="errors" for="options,verification,verification.*,verification.*.*"/>
    <div style="height: 70px"></div>


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
import VueMultiselect from "vue-multiselect";

let _optionCounter = 0;
let _categoryCounter = 0;

export default {
    name: "quiz-edit-match",
    components: {
        Checkbox,
        InputError,
        TextareaAutosize,
        Field,
        draggable,
        Radio,
        ckeditor: CKEditor.component,
        VueMultiselect
    },
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
            lCategories: [],
            multiple: true,
            allowEmpty: true,
            editor: Editor
        }
    },
    methods: {
        removeOption(index) {
            this.lOptions.splice(index, 1);
        },
        addOption() {
            this.lOptions.push({
                index: _optionCounter++,
                text: '',
                text_en: '',
                matches: []
            });
            let $v = this;
            setTimeout(function () {
                $v.$refs.optionTextInput.$el.ckeditorInstance.editing.view.focus();
            }, 100);
        },
        removeCategory(index) {
            let categoryIndex = this.lCategories[index].index;
            for (let option of this.lOptions) {
                option.matches = option.matches.filter(itm => itm.index !== categoryIndex);
            }
            this.lCategories.splice(index, 1);
        },
        addCategory() {
            this.lCategories.push({
                index: _categoryCounter++,
                text: '',
                text_en: ''
            });
            let $v = this;
            setTimeout(function () {
                $v.$refs.categoryTextInput.focus();
            }, 100);
        },

        update() {
            if(!this.multiple){
                for (let option of this.lOptions) {
                    option.matches.splice(1);
                }
            }
            let $v = this;
            let options = this.lOptions.map((itm) => {return {text: itm.text, text_en: itm.text_en};});
            let categories = this.lCategories.map((itm) => {return {text: itm.text, text_en: itm.text_en};});
            let matches = this.lOptions.map(itm => itm.matches.map(function(match){
                return $v.lCategories.findIndex(option => option.index == match.index);
            }).filter(itm => itm != -1));
            this.$emit('update:options', {options: options, categories: categories, allowEmpty: this.allowEmpty, multiple: this.multiple});
            this.$emit('update:verification', {matches: matches});
        }

    },
    watch: {
        lOptions: {
            deep: true,
            handler() {
                this.update();
            }
        },
        lCategories: {
            deep: true,
            handler() {
                this.update();
            }
        },
        multiple(value) {
            this.update();
        },
        allowEmpty(){
            this.update();
        }
    },
    created() {
        _optionCounter = 0;
        _categoryCounter = 0;
        if (_isObject(this.options) && _isArray(this.options.options)) {
            this.lOptions = this.options.options.map(function (itm) {
                itm.index = _optionCounter++;
                itm.matches = [];
                return itm;
            });
        } else {
            this.lOptions = [];
        }


        if (_isObject(this.options) && _isArray(this.options.categories)) {
            this.lCategories = this.options.categories.map(function (itm) {
                itm.index = _categoryCounter++;
                return itm;
            });
        } else {
            this.lCategories = [];
        }


        if (_isObject(this.verification) &&
            this.verification.hasOwnProperty('matches')) {

            for (const m1 in this.verification.matches) {
                for (const m2 of this.verification.matches[m1]) {
                    this.lOptions[m1].matches.push(this.lCategories[m2]);
                }
            }
        }

    }
}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";

:deep(.option) {
    //border-radius: 5px;
    //padding-left: 10px;
    //position: relative;
    //border-left: 2px solid $shadow-color;
    border-radius: 11px;
    padding-left: 10px;
    margin-left: 30px;
    margin-bottom: 20px;
    position: relative;
    border-left: 2px solid #e9e9e9;

    &:focus-within {
        border-left: 2px solid $base-color;
    }

    .option-counter {
        font-size: 1.2em;
        position: absolute;
        left: -35px;
        bottom: 0;
        width: 35px;
        padding-left: 5px;
        text-align: left;
        top: 9px;
        cursor: grab;

        &:active {
            cursor: grabbing;
        }
    }

    .btn-remove {
        color: $dark-shadow-color;
        font-size: 0.9em;
        padding: 0;

        &:hover {
            color: $attractive-color;
        }

    }


    &:not(.vertical) .field .input-label {
        width: 230px;
    }

    .input-label {
        cursor: grab
    }

    .field-remove {
        margin-top: -10px;
        margin-bottom: 0;
        min-height: unset;
        .field-inner {
            display: flex;
            align-items: flex-start;
            justify-content: flex-end;
        }
    }

}

.add-option {
    width: 200px;
}

</style>
<style lang="scss">
.ghost {
    border-color: #ccc;
    opacity: 0.5;
}
</style>
