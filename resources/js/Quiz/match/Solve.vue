<template>

    <div class="options-columns">
        <ul>
            <li v-for="(option, index) of question.options.options">
                <span class="counter">{{index + 1}}</span>
                <div class="ck-content" v-html="option.text"></div>
            </li>
        </ul>
        <ul>
            <li v-for="(option, index) of question.options.categories">
                <span class="counter">{{$filters.ntl(index + 1)}}</span>
                <div class="ck-content" v-html="option.text"></div>
            </li>
        </ul>
    </div>

    <table class="compliance-table" style="font-size: 1.2em">
        <tr>
            <th v-for="(option, index) in question.options.options">{{index + 1}}</th>
        </tr>
        <tr>
            <td v-for="(option, index) in question.options.options" style="width: 80px">
                <vue-multiselect
                    :options="question.options.categories" v-model="choices[index]" track-by="index" label="label"
                    placeholder=""
                    :show-labels="false"
                    :multiple="false"
                />
            </td>
        </tr>
    </table>
    <input-error :errors="errors" for="solution.**"/>

</template>

<script>

import InputError from "@/Components/InputError.vue";
import VueMultiselect from "vue-multiselect";

export default {
    name: "quiz-solve-match",
    components: {InputError,
        VueMultiselect},
    props: {
        errors: {
            default: null,
            type: Object
        },
        question: {
            default: null,
            type: Object
        },
        valid: {
            default: false, type: Boolean
        },
        solution: {
            default: null,
            type: Object
        },
    },
    emits: ['update:solution', 'update:valid'],
    data(){
        let choices = [];
        for (const oIndex of this.question.options.options) {
            choices.push(null)
        }
        return {
            choices: choices
        };
    },

    watch:{
      choices: {
          deep: true,
          handler(){
              let valid = true;
              let choices = [];
              console.log(this.choices);
              for (const choice of this.choices) {
                  if (choice == null) {
                      valid = false;
                      choices.push(null);
                  } else {
                      choices.push(choice.index);
                  }
              }
              this.$emit('update:solution', {choices: choices});
              this.$emit('update:valid', valid);
          }
      }
    },

    created() {
        for (const index in this.question.options.categories) {
            this.question.options.categories[index].index = parseInt(index);
            this.question.options.categories[index].label = this.$filters.ntl(parseInt(index) + 1);
        }
        this.$emit('update:valid', false);
    }
}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";

.options-columns{
    margin-bottom: 1.5em;
    padding-bottom: 1em;
    border-bottom: 1px solid #E7E7E7;
}
@media screen and (min-width: 1000px){
    .options-columns{
        display: flex;
        gap: 50px;
        ul{
            flex-basis: 0;
            flex-grow: 1;
        }
    }
}
@media screen and (max-width: 1000px){
    .options-columns{
        display: flex;
        flex-direction: column;
        gap: 50px;
    }
}


li {
    margin-bottom: 2em;
    position: relative;
    padding-left: 45px;
    .counter {
        font-size: 1.2em;
        position: absolute;
        left: 0;
        bottom: 0;
        width: 35px;
        padding-left: 5px;
        text-align: left;
        top: -4px;
    }
}


.compliance-table {

    margin: 0 0 10px 0;
    td, th {

        vertical-align: middle;
    }

    th {
        padding: 5px 5px 5px 20px;
        text-align: left;
    }
    td{
        padding: 5px;
    }

    :deep(.multiselect){
        height: 47px;
        .multiselect__tags{
            height: 47px;
        }
        .multiselect__single{
            font-size: 1.2em;
            padding-left: 5px;
        }
        .multiselect__select{
            width: 32px;
            height: 44px;
        }
        .multiselect__input {
            min-height: 28px;
        }
    }


}


</style>

