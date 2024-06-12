<script>

export default {
    props: {
        value: {
            default: null
        },
        modelValue: {
            type: [Array, Boolean, String, Number],
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
    <label class="radio">
        <input type="radio" :value="value" v-model="proxy" :disabled="disabled">
        <i class="mark"></i>
        <span v-if="!!$slots.default"><slot /></span>
    </label>
</template>

<style scoped lang="scss">

.radio {
    position: relative;
    user-select: none;
    font-weight: normal;
    display: inline-block;
    cursor: pointer;

    input[type=radio] {
        position: absolute;
        cursor: pointer;
        opacity: 0;
        height: 0;
        width: 0;
    }

    i.mark{
        position: absolute;
        left: 0;
        padding: 0;
        text-align: center;
        box-sizing: border-box;
    }

    //input[type=radio]:checked + i.mark{
    //    border: 2px solid #1AB69D;
    //    background: #1AB69D url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQiIGhlaWdodD0iMTAiIHZpZXdCb3g9IjAgMCAxNCAxMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMy43MDcxIDAuMjkyODkzQzE0LjA5NzYgMC42ODM0MTcgMTQuMDk3NiAxLjMxNjU4IDEzLjcwNzEgMS43MDcxMUw1LjcwNzExIDkuNzA3MTFDNS4zMTY1OCAxMC4wOTc2IDQuNjgzNDIgMTAuMDk3NiA0LjI5Mjg5IDkuNzA3MTFMMC4yOTI4OTMgNS43MDcxMUMtMC4wOTc2MzExIDUuMzE2NTggLTAuMDk3NjMxMSA0LjY4MzQyIDAuMjkyODkzIDQuMjkyODlDMC42ODM0MTcgMy45MDIzNyAxLjMxNjU4IDMuOTAyMzcgMS43MDcxMSA0LjI5Mjg5TDUgNy41ODU3OUwxMi4yOTI5IDAuMjkyODkzQzEyLjY4MzQgLTAuMDk3NjMxMSAxMy4zMTY2IC0wLjA5NzYzMTEgMTMuNzA3MSAwLjI5Mjg5M1oiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=") center center no-repeat;
    //    background-size: 0.8em;
    //}

    //input[type=radio]:focus + i.far{
    //    z-index: 50;
    //    outline: 1px dotted #838383;
    //    outline-offset: 0.8px;
    //}
    //
    //input[type=radio]:checked:disabled + i.far,
    //input[type=radio]:disabled + i.far{
    //    background: #f4f4f4;
    //    border-color: #a9afbb;
    //    color: #636466;
    //}
}

</style>
