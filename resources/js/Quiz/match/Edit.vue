<template>

    <div class="options-columns">
        <div class="options-column" >

            <h2>Категории <span v-if="lOptions.length">({{ lOptions.length }})</span></h2>
            <draggable
                v-if="lOptions.length"
                v-model="lOptions"
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
                            <field class="field-remove">
                                <a class="btn-remove" title="Удалить вариант" @click="removeOption(index)">Удалить <i
                                    class="fa fa-times"></i></a>
                            </field>
                        </div>
                    </div>
                </template>
            </draggable>
            <a class="btn btn-primary btn-xs add-option" @click="addOption">Добавить категорию</a>
            <input-error :errors="errors" for="options.options"/>
        </div>
        <div class="options-column" >
            <h2>Варианты <span v-if="lOptions.length">({{ lOptions.length }})</span></h2>
            <draggable
                v-if="lCategories.length"
                v-model="lCategories"
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
                               :for="'options.categories.' + element.index + '.text'" class="field-option">
                            <ckeditor v-model="element.text" :editor="editor" ref="categoryTextInput" :config="{
                            width: '100%'
                        }"/>
                        </field>
                        <field :errors="errors"
                               label="Английский текст"
                               :for="'options.categories.' + element.index + '.text_en'" class="field-option">
                            <ckeditor v-model="element.text_en" :editor="editor" :config="{
                            width: '100%'
                        }"/>
                        </field>

                        <field class="field-remove">
                            <a class="btn-remove" title="Удалить вариант" @click="removeCategory(index)">Удалить <i
                                class="fa fa-times"></i></a>
                        </field>
                    </div></div>
                </template>
            </draggable>
            <a class="btn btn-primary btn-xs add-option" @click="addCategory">Добавить вариант</a>
            <input-error :errors="errors" for="options.categories"/>
        </div>
    </div>
    <div style="height: 70px"></div>
    <h2>Верный ответ</h2>
    <table class="compliance-table" style="font-size: 1.2em">
        <tr>
            <th v-for="(option, index) in lOptions">{{index + 1}}</th>
        </tr>
        <tr>
            <td v-for="option in lOptions" style="width: 80px">
                <vue-multiselect
                    :options="categoryOptions" v-model="option.match" track-by="index" label="label"
                    placeholder=""
                    :show-labels="false"
                />
            </td>
        </tr>
    </table>
    <input-error :errors="errors" for="verification,verification.*,verification.*.*"/>

    <h2 style="margin-top: 2em">Распределение баллов</h2>
    <br/>
    <checkbox v-model="auto">Заполнить автоматически</checkbox>
    <br><br>
    <table class="weight-table">
        <tr>
            <th :colspan="this.lOptions.length  + 1" style="font-size: 0.8em">Правильные ответы</th>
        </tr>
        <tr>
            <th v-for="w in this.lOptions.length + 1" :class="{'wrong':w == 1, 'right': w != 1}">
                {{ w - 1 }}
            </th>
        </tr>
        <tr>
            <td v-for="w in this.lOptions.length + 1">
                <input type="number" v-model="weights[w - 1]" class="input" style="width: 100px">
            </td>
        </tr>
    </table>
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
import _throttle from "lodash/throttle";
import _debounce from "lodash/debounce";

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
        maxWeight: 0,
    },
    emits: ['update:options', 'update:verification',],

    data() {
        return {
            lOptions: [],
            lCategories: [],
            categoryOptions: [],
            matches: [],
            auto: true,
            weights: [],
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
                $v.$refs.categoryTextInput.$el.ckeditorInstance.editing.view.focus();
            }, 100);
        },

        update: _debounce(function() {
            let $v = this;
            let options = this.lOptions.map((itm) => {return {text: itm.text, text_en: itm.text_en};});
            let categories = this.lCategories.map((itm) => {return {text: itm.text, text_en: itm.text_en};});
            let matches = this.lOptions.map(function(option){
                if(option.match != null){
                    let index = $v.lCategories.findIndex(category => category.index == option.match.index);
                    if (index == -1) {
                        index = null;
                    }
                    return index;
                } else {
                    return null;
                }
            });
            this.$emit('update:options', {options: options, categories: categories});
            this.$emit('update:verification', {matches: matches, weights: this.weights});
        }, 100),

        init(){
            _optionCounter = 0;
            _categoryCounter = 0;
            let $v = this;
            if (_isObject(this.options) && _isArray(this.options.categories)) {
                this.lCategories = this.options.categories.map(function (itm) {
                    itm.index = _categoryCounter++;
                    return itm;
                });
            } else {
                this.lCategories = [];
            }

            if (_isObject(this.options) && _isArray(this.options.options)) {
                this.lOptions = this.options.options.map(function (itm) {
                    itm.index = _optionCounter++;
                    itm.match = null;
                    return itm;
                });
            } else {
                this.lOptions = [];
            }

            if (_isObject(this.verification) &&
                this.verification.hasOwnProperty('matches')) {
                for (const m1 in this.verification.matches) {
                    let index = $v.lCategories.findIndex(category => category.index == this.verification.matches[m1] )
                    if(index != -1) {
                        this.lOptions[m1].match = {
                            index: index,
                            label: this.$filters.ntl(index + 1)
                        }
                    }

                }
            }
        },
        updateWeights(){
            let mw = this.maxWeight ? this.maxWeight : this.lOptions.length;
            if(this.auto){
                for (let w = 0; w <= this.lOptions.length; w++) {
                    this.weights[w] = this.auto ? Math.ceil(w * mw / this.lOptions.length) : 0;
                }
            } else {
                for (let w = 0; w <= this.lOptions.length; w++) {
                    if(w >= this.weights.length) {
                        this.weights[w] = 0;
                    }
                }
            }
        }
    },
    watch: {
        lOptions: {
            deep: true,
            handler() {
                this.updateWeights();
                this.update();
            }
        },
        lCategories: {
            deep: true,
            handler() {
                this.updateWeights();
                this.categoryOptions.length = 0;
                for (let i = 0; i < this.lCategories.length; i++) {
                    this.categoryOptions.push({
                        index: this.lCategories[i].index,
                        label: this.$filters.ntl(i + 1)
                    })
                }
                for (let option of this.lOptions) {
                    if(option.match != null){
                        option.match = this.categoryOptions.find(itm => itm.index == option.match.index);
                    }
                }
                this.update();
            }
        },
        weights: {
            deep: true,
            handler(n, o){
                this.update();
            }
        },
        maxWeight(){
            this.updateWeights();
        },
        auto(value){
            if(value){
                this.updateWeights();
                this.update();
            }
        },
        errors(){
            this.init()
        }
    },
    created() {
        this.init()
    }
}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";

@media screen and (min-width: 1000px){
    .options-columns{
        display: flex;
        gap: 50px;
        .options-column{
            flex-basis: 0;
            flex-grow: 1;
        }
    }
}
@media screen and (max-width: 1000px){
    .options-columns{
        display: flex;
        flex-direction: column;
        gap: 50px;

    }
}


:deep(.option) {
    border-radius: 11px;
    padding-left: 15px;
    margin-left: 25px;
    margin-bottom: 20px;
    position: relative;
    border-left: 2px solid #e9e9e9;

    &:focus-within {
        border-left: 2px solid $base-color;
    }

    .option-counter {
        font-size: 1.2em;
        position: absolute;
        left: -30px;
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

.compliance-table {

    margin: 0 0 10px 0;
    td, th {

        vertical-align: middle;
    }

    th {
        padding: 5px 5px 5px 20px;
        text-align: left;
    }
    td{
        padding: 5px;
    }

    :deep(.multiselect){
        height: 47px;
        .multiselect__tags{
            height: 47px;
        }
        .multiselect__single{
            font-size: 1.2em;
            padding-left: 5px;
        }
         .multiselect__select{
            width: 32px;
             height: 44px;
        }
        .multiselect__input {
            min-height: 28px;
        }
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

    .wrong {background-color: #ffecec}
    .right {background-color: #ecffed}

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
