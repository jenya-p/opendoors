<template>
    <div v-show="isActive">
        <slot></slot>
    </div>
</template>
<script>
export default {
    name: 'tab',
    props: {
        name: {required: true},
        label: {required: true},
        selected: {default: false},
        iconClass: {default: null},
        hasError:  false
    },
    data() {
        return {isActive: false}
    },
    computed: {
        href() {
            return '#' + this.name.toLowerCase().replace(/ /g, '-');
        }
    },
    created() {
        this.$parent.tabs.push(this);
        if(this.$parent.activeTab == null){
            this.$parent.selectTab(this);
        }
    },
    mounted() {
        this.isActive = this.selected;
    }
}
</script>
