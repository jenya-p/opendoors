<template>
    <vue-multiselect :options="options" v-model="proxy" track-by="id" label="label"/>
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
        let yearOptions = [];
        for(let y = 2025; y > 1950; y--){
            yearOptions.push({id: y, label: y});
        }
        return {
            options: yearOptions
        }

    },
    emits: ['update:modelValue'],
    computed: {
        proxy: {
            get() {
                let $v = this;
                if (this.modelValue) {
                    return this.options.find(itm => itm.id == $v.modelValue);
                }
            },
            set(value) {
                if (value) {
                    if(this.options.findIndex(itm => itm.id == value.id) === -1){
                        this.options.push(value);
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
