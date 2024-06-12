<template>

    <VueMultiselect
        class="user-select"
        ref="multiselect"
        placeholder="Имя или Емайл"
        selectLabel="выбрано"
        selectedLabel="Enter что бы выбрать"
        :taggable="false"
        :searchable="true"
        v-model="value"
        :options="options"
        track-by="id"
        label="display_name"
        @search-change="getOptions"
        @close="close"
        :internal-search="false"
        :allow-empty="allowEmpty">
        <template #option="props">
            <div class="primary">{{ props.option.name }}</div>
            <div class="secondary">{{ props.option.email }}</div>
        </template>
    </VueMultiselect>
</template>

<script>
import VueMultiselect from "vue-multiselect";

export default {
    components: {VueMultiselect},
    props: {
        modelValue: {
            type: Object,
            default: null
        },
        allowEmpty: Boolean,
    },
    emits: ['update:modelValue'],
    data() {
        return {
            options: [],
        }
    },
    methods: {
        getOptions(query) {
            var $v = this;
            var params = {
                query: query
            };

            axios.get('/admin/user/suggest', {params: params, responseType: 'json'})
                .then((responce) => {
                    $v.options = responce.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        close(){}
    },
    computed: {
        value: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit("update:modelValue", value);
            }
        }
    },
    created() {
      this.getOptions();
    }

}
</script>
<style lang="scss">
@import "resources/css/admin-vars";
.multiselect.user-select{
    .multiselect__option {

        .secondary{

            font-size: 0.8em;
        }
    }
}
</style>
