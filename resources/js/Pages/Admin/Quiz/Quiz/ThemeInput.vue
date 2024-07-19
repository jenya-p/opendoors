<template>

    <div class="theme-inner">

        <template v-if="theme!= null && edit">
            <input type="text" v-model="name" class="input"
                   @keydown="keydown($event)"
                   @blur="blur"
                   ref="input"
            >
        </template>
        <template v-else>
            <vue-multiselect :options="options"
                             v-model="theme"
                             :taggable="true"
                             track-by="name"
                             label="name"
                             @tag="addTheme($event, element)"
            />
            <a @click="startEditing" :class="{disabled: theme == null}" class="fa fa-pencil btn btn-edit" title="Редактировать название Направления МКН"></a>

        </template>
    </div>
</template>
<script>
import VueMultiselect from "vue-multiselect";



export default {
    components: {VueMultiselect},
    props: {
        value: {
            type: Object,
            default: null
        },
        options: {
            type: Array,
            default: []
        }
    },
    emits: ['update:options', 'update:value'],
    data() {
        return {
            edit: false
        }
    },
    watch: {},
    methods: {
        startEditing(){
            if(this.theme){
                this.name = this.theme.name;
                this.edit = true;
                this.$nextTick(function(){
                    this.$refs.input.focus();
                });
            }

        },
        addTheme(value, element) {
            let opt = {
                'name': value
            };
            let o = this.options;

            o.push(opt);
            this.$emit('update:options', o);
            this.$emit('update:value', opt);
        },
        blur(){
            this.edit = false;
        },
        keydown(event) {
            if(event.key == 'Enter'){
                if(this.name.trim() !== ''){
                    let o = this.options;
                    let index = o.findIndex(itm => itm.name == this.theme.name)
                    console.log(o, this.theme.name, index);
                    if(index !== -1){
                        o[index].name = this.name;
                        this.$emit('update:options', o);
                        this.$emit('update:value', o[index]);
                    }
                }
                this.edit = false;
                return false;
            } else if(event.key == 'Escape'){
                this.edit = false;
                return false;
            }
        }
    },
    computed: {
        theme: {
            get() {
                return this.value;
            },
            set(val){
                this.$emit('update:value', val);
            }
        }
    }

}
</script>
<style lang="scss" scoped>
@import "/resources/css/admin-vars";

.theme-inner{
    display: flex;
    align-items: center;


    input {
        padding-left: 8px;
        border-radius: 0;
    }

    .btn {
        width: 40px;
        text-align: center;
        padding: 0;
        line-height: 40px;
        color: $light-fore-color;
        border-radius: 3px;
        &.disabled{
            pointer-events: none;
            opacity: 0;
        }
        &:hover {
            color: $fore-color;
        }
    }

}


</style>
