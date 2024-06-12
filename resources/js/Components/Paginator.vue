<template>
    <div class="pagination" v-if="lPageCount != 0">
        <p class="">{{ label }}</p>
        <template v-for="i in pages">
            <span v-if="i=='sep'" class="separator">...</span>
            <span v-else-if="i == current" class="active">{{ i }}</span>
            <a v-else @click="go(i)">{{ i }}</a>
        </template>
    </div>
</template>

<script>
export default {
    name: "pagination",
    data() {
        return {
            current: this.modelValue,
        }
    },
    props: {
        itemCount: {
            type: Number,
            required: false,
        },
        ipp: {
            type: Number,
            required: false,
            default: 10
        },
        pageCount: {
            type: Number,
            required: false,
        },
        label: {
          type: String,
          default: 'Страницы:'
        },
        modelValue: ''
    },
    methods: {
        go(page) {
            this.current = page;
            this.$emit('update:modelValue', page);
        }
    },
    computed: {
        lPageCount() {
            if(this.pageCount){
                return this.pageCount;
            } else if (this.itemCount){
                return Math.ceil(this.itemCount / this.ipp);
            } else {
                return 0
            }
        },
        pages() {
            var pages = [];
            for (var i = 1; i <= this.lPageCount; i++) {
                if (i === 1 || i === this.lPageCount || (i > Math.min(this.lPageCount - 7, this.current - 3) && i < Math.max(8, this.current + 3))) {
                    pages.push(i);
                } else if (i === 2 || (i === this.lPageCount - 1 && this.current !== 'all')) {
                    pages.push('sep');
                }
            }
            return pages;
        }
    },
    watch: {
        modelValue() {
            this.current = this.modelValue;
        }
    }
}
</script>

<style lang="scss" scoped>
.pagination {
    @import "resources/css/admin-vars";

    width: auto;
    margin: 0 5px 0 auto;
    display: flex;

    .separator {
        font-size: 14px;
        padding: 0;
        width: 30px;
        text-align: center;
        display: inline-block;
        color: gray;
        line-height: 25px;
        margin-left: 7px;
    }

    p {
        font-size: 14px;
        color: #666666;
        margin: 0;
        padding: 0 5px 0 0 ;
        line-height: 32px;
    }

    .active,a {
        font-size: 14px;
        padding: 0;
        width: 30px;
        margin-left: 7px;
        text-align: center;
        display: inline-block;
        box-sizing: border-box;
        line-height: 30px;
        border-radius: 7px;
        height: 30px;
    }

    .active {
        color: white;
        background: $attractive-color;
        border: 1px solid transparent;
    }

    a {
        text-decoration: none;
        color: inherit;
        cursor: pointer;
        border: 1px solid $shadow-color;
    }
}
</style>
