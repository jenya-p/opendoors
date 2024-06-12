<script>
import {isString, isArray, isObject, isEmpty} from "lodash"

export default {
    props: ['errors', 'label', 'for', 'description'],
    computed: {
        hasErrors() {
            return this.filteredErrors.length != 0;
        },
        filteredErrors() {
            let e = [];
            let lFor = this.for;
            if (isString(lFor)) {
                lFor = lFor.split(',');
            }
            let errors = this.errors;
            if (this.errors == undefined && this.$parent && this.$parent.form && this.$parent.form.errors) {
                errors = this.$parent.form.errors;
            }
            if (isArray(lFor) && isObject(errors)) {
                for (const filter of lFor) {
                    let reg = '^' + filter.replaceAll('.?', '(.\\w+)?')
                        .replaceAll('*', '\\w+')
                        .replaceAll('.', '\\.') + '$';
                    reg = new RegExp(reg);
                    for (const key in errors) {
                        if (reg.test(key) && !isEmpty(errors[key])) {
                            e.push(errors[key]);
                        }
                    }
                }
            } else {
                e = errors;
                if (isString(e)) {
                    e = [e];
                } else {
                    e = [];
                }
            }
            return e;
        },
        fieldClass() {
            if(this.for){
                return 'field field-' + this.for;
            } else {
                return 'field';
            }
        }
    },

}


</script>

<template>
    <div :class="fieldClass" ref="wrapper">
        <label v-if="$slots.label" class="input-label" ><slot name="label"/></label>
        <label class="input-label" v-else-if="label!==undefined">{{ label }}</label>
        <div>
            <div class="field-inner" :class="{'has-error': hasErrors}">
                <slot></slot>
            </div>
            <p v-for="err of filteredErrors" class="input-error">{{ err }}</p>
            <small v-if="!hasErrors && description" class="input-description">{{ description }}</small>
        </div>
    </div>
</template>


