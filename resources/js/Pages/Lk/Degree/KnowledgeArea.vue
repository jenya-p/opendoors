<template>
    <div class="wrapper">
        <div>
            <label>Научное направление</label>
            <vue-multiselect :options="knowledge_tree" v-model="item1" track-by="id" label="name"/>
        </div>
        <div>
            <label>Область знания</label>
            <vue-multiselect :options="item1 == null ? []: item1.items" v-model="item2" track-by="id" label="name" :disabled="item1 == null"/>
        </div>
        <div>
            <label>Блок областей знания</label>
            <vue-multiselect :options="item2 == null ? []: item2.items" v-model="item3" track-by="id" label="name" :disabled="item1 == null"/>
        </div>

        <a class="fa fa-times btn-remove" @click.stop="$emit('remove')"></a>
    </div>
</template>


<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TableBottom from "@/Components/TableBottom.vue";
import {router, useForm} from "@inertiajs/vue3";
import {closeEditor, selectable} from "@/Components/utils.js";
import Field from "@/Components/Field.vue";
import VueMultiselect from "vue-multiselect";
import Checkbox from "@/Components/Checkbox.vue";
import Attachments from "@/Components/Attachments.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import InputError from "@/Components/InputError.vue";
import _extend from "lodash/extend";

export default {
    components: {InputError, TextareaAutosize, Attachments, Checkbox, Field, TableBottom, AdminLayout, VueMultiselect},
    props: [
        'knowledge_tree',
        'modelValue',
    ],
    emits: ['remove', 'update:modelVale'],
    data() {
        return {
            item1: null,
            item2: null,
            item3: null,
        }
    },
    methods: {
        refresh() {
            if (this.modelValue == null) {
                this.item3 = null;
            } else {
                for (const item1 of this.knowledge_tree) {
                    for (const item2 of item1.items) {
                        for (const item3 of item2.items) {
                            if (item3.id == this.modelValue) {
                                this.item1 = item1;
                                this.item2 = item2;
                                this.item3 = item3;
                            }
                        }
                    }
                }
            }
        }
    },
    watch: {
        item3(value) {
            if (value !== null) {
                this.$emit('update:modelValue', value.id);
            } else {
                this.$emit('update:modelValue', null);
            }
        },
        modelValue(value) {
            this.refresh();
        }
    },
    computed: {},
    created() {
        this.refresh();
    }

}

</script>
<style lang="scss" scoped>
.wrapper {
    display: flex;
    align-items: flex-end;
    gap: 20px;
    margin-bottom: 15px;

    div {
        width: 100%;
        flex-shrink: 1;

        label {
            margin-bottom: 5px;
            display: inline-block;
            font-weight: 500;
            font-size: 0.9em;
        }
    }

    a {
        margin-bottom: 4px
    }
}

</style>
<i18n>
{
    "ru": {
    },
    "en": {
    }
}
</i18n>
