<template>
    <template v-if="schema.type == 'array'">
        <h2 v-if="schema.label">{{label()}}</h2>
        <draggable
            v-if="proxy.length"
            v-model="proxy"
            handle=".json-option-counter"
            group="group"
            item-key="index"
            ghost-class="ghost"
            animation="200"
        >
            <template #item="{element, index}">
                <div class="json-option" v-field-container>
                    <span class="json-option-counter">{{ index + 1 }}</span>
                    <json-input :schema="schema.items" v-model="proxy[index]"/>
                    <div class="json-option-remove">
                        <a class="btn-remove" title="Удалить элемент списка" @click="removeItem(index)"><i
                            class="fa fa-times"></i> &nbsp;&nbsp;Удалить </a>
                    </div>
                </div>
            </template>
        </draggable>
        <a class="btn btn-primary btn-xs add-option" @click="addItem">Добавить</a>
    </template>
    <template v-else-if="schema.type == 'object'">
        <div class="object">
            <div v-for="(prop, key) of schema.properties">
                <template v-if="prop.translable">
                    <template v-if="prop.type == 'attachment'">
                        <field :label="prop.label" class="field-row">
                            <span class="json--attachments-lang">
                                <label>Рус</label>
                                <json-attachment v-model="proxy[key]" :schema="prop"/>
                            </span>
                            <span class="json--attachments-lang">
                                <label>Англ</label>
                                <json-attachment v-model="proxy[key + '_en']" :schema="prop"/>
                            </span>
                        </field>
                    </template>
                    <template v-else>
                        <json-input :schema="prop" v-model="proxy[key]"/>
                        <json-input :schema="prop" v-model="proxy[key + '_en']" lang="En"/>
                    </template>
                </template>
                <json-input :schema="prop" v-model="proxy[key]" v-else/>
            </div>
        </div>
    </template>
    <field v-else-if="schema.type=='url'" :label="label()">
        <input type="url" v-model="proxy" class="input">
    </field>
    <field v-else-if="schema.type=='string'" :label="label()">
        <input type="text" v-model="proxy" class="input">
    </field>
    <field v-else-if="schema.type=='email'" :label="label()">
        <input type="email" v-model="proxy" class="input">
    </field>
    <field v-else-if="schema.type=='ml-string'" :label="label()">
        <textarea-autosize type="text" v-model="proxy" class="input"/>
    </field>
    <field v-else-if="schema.type=='boolean'" label="">
        <checkbox v-model="proxy">{{label()}}</checkbox>
    </field>
    <field v-else-if="schema.type=='select'" :label="label()">
        <json-select :schema="schema" v-model="proxy" />
    </field>
    <field v-else-if="schema.type=='year'" :label="label()">
        <json-year :schema="schema" v-model="proxy" />
    </field>
    <field v-else-if="schema.type=='editor'" :label="label()">
        <editor v-model="proxy"/>
    </field>
    <field v-else-if="schema.type=='attachment'" :label="label()">
        <json-attachment v-model="proxy" :schema="schema"/>
    </field>
    <div v-else style="background-color: red">
        {{ schema.type }} - {{ proxy }}
    </div>
</template>

<script>
import Field from "@/Components/Field.vue";
import Attachment from "@/Components/Attachment.vue";
import Editor from "@/Components/Editor.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import JsonAttachment from "@/Components/JsonAttachment.vue";
import draggable from "vuedraggable";
import _isObject from "lodash/isObject";
import _isArray from "lodash/isArray";
import _isUndefined from "lodash/isUndefined";
import Checkbox from "@/Components/Checkbox.vue";
import JsonSelect from "@/Components/JsonSelect.vue";
import JsonYear from "@/Components/JsonYear.vue";

export default {
    components: {JsonYear, JsonSelect, Checkbox, JsonAttachment, TextareaAutosize, Editor, Attachment, Field, draggable},
    props: {
        modelValue: {
            default: null
        },
        schema: {
            type: Object,
            default: null
        },
        lang: null,
        attachment_ids: []
    },
    methods: {
        addItem(){
            this.proxy.push({})
        },
        removeItem(index){
            this.proxy.splice(index, 1);
        },
        translate(schema) {
            schema.label = schema.label + ' (En)';
            return schema;
        },
        label() {
            return this.schema.label + (this.lang ? ' (' + this.lang + ')' : '');
        }
    },
    emits: ['update:modelValue'],
    computed: {
        proxy: {
            get() {
                if(this.schema.type == 'array' && !_isArray(this.modelValue)){
                    return [];
                } else if (this.schema.type == 'object' && !_isObject(this.modelValue)){
                    return {};
                } if(_isUndefined(this.modelValue)){
                    return null;
                }
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        }
    }
}
</script>

<style lang="scss">
@import "/resources/css/admin-vars";
.json-option {
    border-radius: 11px;
    padding-left: 15px;
    margin-left: 25px;
    margin-bottom: 20px;
    position: relative;
    border-left: 2px solid $shadow-color;

    &:focus-within {
        border-left: 2px solid $base-color;
    }

    .json-option-counter {
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

    .json-option-remove{
        display: flex;
        /** justify-content: flex-end; */
        padding-bottom: 0.4em;
        .btn-remove {
            color: $dark-shadow-color;
            font-size: 0.9em;
            padding: 0;
            margin: 0;
            &:hover {
                color: $attractive-color;
            }
        }
    }
}
.add-option{
    margin-left: 25px;
}
.json--attachments-lang {
    display: inline-flex;
    align-content: center;
    flex-direction: column;

    margin-right: 30px;
    margin-bottom: 20px;

    & > label {
        font-weight: 500;
        font-size: 0.9em;
        width: 100%;
        border-bottom: 1px solid $shadow-color;
        padding-bottom: 5px;
        margin-bottom: 10px;
    }
}
.ghost {
    border-color: #ccc;
    opacity: 0.5;
}
</style>
