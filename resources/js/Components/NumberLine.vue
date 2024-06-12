<template>
    <div class="number-line">
        <label v-if="label">{{ label }}</label>
        <div class="number-line-inner" ref="container" :class="{rollers: showRollers}">
            <a @click="roll(-1)" class="roller roller-left" v-if="showRollers" :class="{disabled: !leftRoller}"></a>

            <template v-for="index in numbers">
                <span v-if="index==='s'" class="spacer">...</span>
                <a v-else-if="index != current" @click="current = index" :class="itemClass1(index)">{{ index }}</a>
                <span v-else :class="itemClass1(index)">{{ index }}</span>
            </template>

            <a @click="roll(1)" class="roller roller-right" v-if="showRollers" :class="{disabled: !rightRoller}"></a>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        label: {
            type: String,
            default: null,
        },
        modelValue: {
            type: Number,
            default: 0
        },
        count: {
            type: Number
        },
        itemClass: {
            type: Function
        },
    },
    data() {
        return {
            showRollers: true,
            numbers: [],
            pos: 2,
            leftSpacer: false,
            tightSpacer: false,
            leftRoller: false,
            rightRoller: true,
        }
    },
    computed: {
        current: {
            get() {
                return this.modelValue;
            },
            set(val) {
                this.$emit("update:modelValue", val);
            },
        }
    },
    methods: {
        itemClass1(index){
            let cl = [];
            if(this.itemClass){
                cl.push(this.itemClass(index));
            }
            if(index != 1 && index != this.count){
                cl.push('movable');
            }
            return cl;
        },
        resize(checkOverflow = true) {
            let container = this.$refs.container;
            if(container == undefined) return;

            let containerWidth = container.clientWidth;
            let elWidth = 24 + 5;
            if(this.$el.classList.contains('number-line--white')){
                elWidth = 28 + 5;
            };

            let maxCount = Math.ceil( containerWidth / elWidth);

            this.numbers.length = 0;
            if(maxCount > this.count){
                this.showRollers = false;
                this.leftRoller = false;
                this.rightRoller = false;

                for (let i = 1; i <= this.count; i++) {
                    this.numbers.push(i);
                }

            } else {
                this.showRollers = true;
                maxCount = maxCount - 5;
                this.pos = Math.min(this.pos, this.count - maxCount);

                if(checkOverflow){
                    if(this.modelValue < this.pos){
                        this.pos = Math.max(2, this.modelValue);
                    }
                    if(this.modelValue > this.pos + maxCount - 2){
                        this.pos = Math.min(this.count - maxCount, this.modelValue - maxCount + 2);
                    }
                }

                this.numbers.push(1);
                if(this.pos <= 2){
                    this.numbers.push(2);
                    this.leftRoller = false;
                } else {
                    this.numbers.push('s');
                    this.leftRoller = true;
                }

                for (let i = 1; i < maxCount - 1; i++) {
                    this.numbers.push(this.pos + i);
                }

                if(this.pos >= this.count - maxCount){
                    this.numbers.push(this.count - 1);
                    this.rightRoller = false;
                } else {
                    this.numbers.push('s');
                    this.rightRoller = true;
                }

                this.numbers.push(this.count);
            }
        },
        roll(direction) {
            this.pos += direction;
            this.resize(false);
        },
    },
    mounted() {
        window.addEventListener('resize', this.resize);
        this.resize()
    },
    unmounted() {
        window.removeEventListener('resize', this.resize);
    },
    created() {

    },
    watch: {
        modelValue(val){
            this.resize()
        },
        pos(v1, v2){
            let cl = this.$refs.container.classList
            if(v1 > v2){
                cl.add('moving-right');
                setTimeout(function(){
                    cl.remove('moving-right');
                },1);
            } else {
                cl.add('moving-left');
                setTimeout(function(){
                    cl.remove('moving-left');
                },1);
            }

        }
    }
}

</script>

<style scoped lang="scss">

.number-line{
    display: flex;
    gap: 10px;
    align-items: center;
    padding: 5px;
    @media screen and (min-width: 700px) {

    }
    @media screen and (max-width: 700px) {
        flex-direction: column;
    }

    label {
        font-weight: 600;
        @media screen and (max-width: 700px) {
            font-size: 12px;
        }
        @media screen and (min-width: 700px) {
            font-size: 16px;
            flex-shrink: 0;
            flex-grow: 0;
            width: 65px;
            text-align: right;
            &:after{
                content: ': ';
            }
        }
    }

    .number-line-inner {
        display: flex;
        align-items: center;
        gap: 0px;
        overflow: hidden;
        width: 100%;

        &.rollers{
            gap: 0;
            justify-content: space-between;
        }
        &:not(.rollers){
            gap: 5px;
            @media screen and (min-width: 700px) {
                justify-content: start;
            }
            @media screen and (max-width: 700px) {
                justify-content: center;
            }
        }

        a,
        span {
            box-sizing: border-box;
            line-height: 25px;
            width: 25px;
            height: 25px;
            text-align: center;
            flex-shrink: 0;
            flex-grow: 0;
            padding: 0;
            margin: 0;
            border-radius: 8px;
            font-family: 'Jura', sans-serif, serif;
        }

        a {
            cursor: pointer;
            transition: filter 200ms ease;
            &:hover {
                text-decoration: none;
                filter: brightness(95%);
            }
        }

        .movable{
            translate: 0 0;
            transition: translate 200ms linear;
        }

        &.moving-left{
            .movable{
                translate: -25px 0;
                transition: none;
            }
        }

        &.moving-right{
            .movable{
                translate: 25px 0;
                transition: none;
            }
        }

        .spacer {
            background: transparent;
            border: 2px solid transparent;
            text-align: center;
            color: #DADADA;
        }

        .roller {
            background: transparent;
            background-position: center center;
            border: 2px solid transparent;
            background-repeat: no-repeat;
            width: 30px;
            transition: background-position 200ms ease;
            &-left {
                background-image: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOSIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDkgMTYiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CiAgICA8cGF0aCBkPSJNOCAxNUwxIDhMOCAxIiBzdHJva2U9IiMxQUI2OUQiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=");
                &.disabled {
                    pointer-events: none;
                    background-image: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOSIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDkgMTYiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CiAgICA8cGF0aCBkPSJNOCAxNUwxIDhMOCAxIiBzdHJva2U9IiNGMUYxRjEiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=");
                }
                &:hover{
                    background-position-x: calc(50% - 1.5px);
                }
            }

            &-right {
                background-image: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOSIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDkgMTYiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CiAgICA8cGF0aCBkPSJNMC45OTk5OTkgMUw4IDhMMSAxNSIgc3Ryb2tlPSIjMUFCNjlEIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K");
                &.disabled {
                    pointer-events: none;
                    background-image: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOSIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDkgMTYiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CiAgICA8cGF0aCBkPSJNMC45OTk5OTkgMUw4IDhMMSAxNSIgc3Ryb2tlPSIjRjFGMUYxIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K");
                }
                &:hover{
                    background-position-x: calc(50% + 1.5px);
                }
            }
        }

    }

    &--gray .number-line-inner {
        label {
            line-height: 24px
        }

        a,
        span {
            font-size: 11px;
            line-height: 22.5px;
            width: 22px;
            height: 22px;
            background: #F1F1F1;
            font-weight: 400;
            box-sizing: content-box;
            border: 2px solid #F1F1F1;
        }

    }

    &--white .number-line-inner {
        label {
            line-height: 27px
        }

        a,
        span {
            font-size: 12px;
            line-height: 24px;
            width: 24px;
            height: 24px;
            box-sizing: content-box;
            flex-shrink: 0;
            flex-grow: 0;
        }


    }


}


</style>
