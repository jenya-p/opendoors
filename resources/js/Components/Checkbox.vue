<script>

export default {
    props: {
        value: {
            default: null
        },
        modelValue: {
            type: [Array, Boolean, String],
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        proxy: {
            get() {
                return this.modelValue;
            },
            set(val) {
                this.$emit("update:modelValue", val);
            },
        }
    }
}

</script>

<template>
    <label class="checkbox">
        <input type="checkbox" :value="value" v-model="proxy" :disabled="disabled">
        <i class="mark" :class="{half:proxy === 'half'}"></i>
        <span v-if="!!$slots.default"><slot/></span>
    </label>
</template>

<style scoped lang="scss">

.checkbox {
    position: relative;
    user-select: none;
    font-weight: normal;
    margin-bottom: 0;
    cursor: pointer;
    display: inline-block;

    input[type=checkbox] {
        position: absolute;
        cursor: pointer;
        opacity: 0;
        height: 0;
        width: 0;
    }


    i.mark {
        position: absolute;
        left: 0;
        padding: 0;
        text-align: center;
        box-sizing: border-box;
    }
}

</style>
