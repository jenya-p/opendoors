<script>
import {isString, isArray, isObject, isEmpty} from "lodash"

export default {
    props: {message: {
            type: String,
        },
        for: {
            type: String,
        },
        errors: {
            type: Object,
        }},

    computed: {
        hasErrors() {
            return this.filteredErrors.length != 0;
        },
        filteredErrors() {
            if(isString(this.message)){
                return [this.message]
            } else if(isArray(this.message)){
                return this.message;
            }
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
            return 'field field-' + this.for;
        }
    },

}

</script>

<template>
    <p v-for="err of filteredErrors" class="input-error">{{ err }}</p>
</template>
