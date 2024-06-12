<template>
<a class="sorter" :class="classes" @click="click">
    <slot></slot>
</a>
</template>

<script>

export default {
    name: 'sort',
    props: {
        name: String,
        modelValue: Object,
        strategy: {
            type: String, default: null
        }
    },

    emits: ['update:modelValue'],

    computed:{
        classes(){
            if(this.modelValue != null && this.modelValue.name === this.name){
                return this.modelValue.dir;
            }
        }
    },

    methods: {
      click(){
        if(this.modelValue == null){
            this.$emit('update:modelValue', {name: this.name, dir: 'asc', strategy: this.strategy});
        } else {
            if(this.modelValue.name !== this.name){
                this.$emit('update:modelValue', {name: this.name, dir: 'asc', strategy: this.strategy});
            } else if(this.modelValue.dir === 'asc'){
                this.$emit('update:modelValue', {name: this.name, dir: 'desc', strategy: this.strategy});
            } else {
                this.$emit('update:modelValue', null);
            }
        }
      }
    }

}

</script>

<style lang="scss">

@import "resources/css/admin-vars.scss";

.sorter{
    position: relative;
    padding-right: 1.1em;
    display: inline-block;
    cursor: pointer;
    user-select: none;
    color: inherit;
    &:hover{
        text-decoration: underline;
    }
    &:after, &:before{
        position: absolute;
        top: 50%;
        right: 0;
        height: 1em;
        margin-top: -0.55em;
        content: '';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        font-style: normal;
        font-variant: normal;
        line-height: 1;
        text-rendering: auto;
        color: $attractive-color;
    }
    &:before{
        color: #dadada;
        content: "\f0dc";
    }
    &.asc:after{
        content: "\f0de";
    }
    &.desc:after{
        content: "\f0dd";
    }
}
</style>
