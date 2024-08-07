<template>
    <vue-multiselect :options="schema.options" v-model="proxy" track-by="id" label="name"/>
</template>

<script>
import Field from "@/Components/Field.vue";
import Attachment from "@/Components/Attachment.vue";
import Editor from "@/Components/Editor.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import VueMultiselect from "vue-multiselect";
import {isArray} from "lodash";

export default {
    components: {VueMultiselect},
    props: {
        modelValue: {
            default: null
        },
        schema: {
            type: Object,
            default: null
        }
    },
    data() {

    },
    emits: ['update:modelValue'],
    computed: {
        proxy: {
            get() {
                let $v = this;
                if (this.modelValue) {
                    return this.schema?.options?.find(itm => itm.id == $v.modelValue);
                }
            },
            set(value) {
                if (value) {
                    if(this.schema.options.findIndex(itm => itm.id == this.modelValue) === -1){
                        this.schema.options.push(value);
                    }
                    this.$emit('update:modelValue', value.id);
                } else {
                    this.$emit('update:modelValue', null);
                }
            }
        }
    }
}
</script>
