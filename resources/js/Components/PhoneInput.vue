<template>
    <input type="text" v-model="proxy" ref="input" @blur="blur">
</template>

<script>
import intlTelInput from 'intl-tel-input';
import 'intl-tel-input/build/css/intlTelInput.min.css';
import {markRaw} from "vue";

export default {
    // components: {VueTelInput},
    props: {
        modelValue: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            itl: markRaw(null),
            proxy: this.modelValue,
        }
    },
    emits: ['update:modelValue', 'update:valid'],
    methods: {
        blur() {
            if (this.itl && this.itl.isValidNumber()) {
                this.$emit('update:valid', true);
            } else {
                this.$emit('update:valid', false);
            }
        }
    },
    watch: {
        proxy(value) {
            if (this.itl) {
                if (this.itl.isValidNumber()) {
                    this.$emit('update:valid', true);
                    this.$emit('update:modelValue', this.itl.getNumber());
                } else {
                    this.$emit('update:modelValue', null);
                }
            }
        }
    },
    mounted() {
        // this.$refs.input.value.set(this.modelValue);
        this.itl = intlTelInput(this.$refs.input, {
            initialCountry: "auto",
            geoIpLookup: callback => {
                fetch("https://ipapi.co/json")
                    .then(res => res.json())
                    .then(data => callback(data.country_code))
                    .catch(() => callback("us"));
            },
            strictMode: true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.7.3/build/js/utils.js",
        });
    },
    unmounted() {
        if (this.itl) {
            this.itl.destroy()
            this.itl = null;
        }
    }
}
</script>
<style scoped lang="scss">
</style>

<style>

</style>

