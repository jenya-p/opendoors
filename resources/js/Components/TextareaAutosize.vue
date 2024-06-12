<template>
    <textarea
        class="autosize"
        @input="handleInput"
        :value="modelValue"
    ></textarea>
</template>

<script>
import _throttle from "lodash/throttle";

if (!window.hasOwnProperty('__textareautosize')) {
    window.__textareautosize = _throttle(function () {
        for (const textarea of document.querySelectorAll('textarea.autosize')) {
            if (textarea.__vnode) {
                textarea.__vnode.ctx.ctx.resize();
            }
        }
    }, 200);
    window.addEventListener('resize', window.__textareautosize);
}

export default {
    name: "TextareaAutosize",
    emits: ['update:modelValue'],
    props: {
        modelValue: {
            required: false,
            type: String
        }
    },
    methods: {
        handleInput(event) {
            this.resize();
            this.$emit('update:modelValue', this.$el.value);
        },
        resize() {
            this.$el.style.height = 'auto'
            this.$el.style.height = `${this.$el.scrollHeight + 3}px`
        }
    },
    mounted() {
        let $el = this.$el;
        setTimeout(function(){
            $el.style.height = 'auto'
            $el.style.height = `${$el.scrollHeight + 3}px`
        },50);

    }
}


</script>

