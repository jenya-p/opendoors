<template>
    <AdminLayout :title="$t('Университеты')">
        <form @submit.prevent="submit" class="block">

            <h2>{{$t('HEADING1')}}</h2>
            <p>{{$t('HEADING2')}}</p>

            <table class="table weight-table">
                <tr>
                    <th></th>
                    <th class="order">{{$t('Приоритет')}}</th>
                    <th class="name">{{$t('Название вуза')}}</th>
                    <th class="remove"></th>
                </tr>
                <draggable
                    tag="tbody"
                    v-model="universities"
                    handle=".handler"
                    item-key="index"
                    ghost-class="ghost"
                    animation="200"
                >
                    <template #item="{element, index}">
                        <tr>
                            <td class="handler-td">
                                <i class=" fa fa-bars handler"></i>
                            </td>
                            <td class="order">
                                {{ index + 1 }}
                            </td>
                            <td class="name">
                                {{ element.name }}
                            </td>
                            <td class="remove">
                                <a @click="remove(index)" class="fa fa-times btn-remove"></a>
                            </td>
                        </tr>
                    </template>
                </draggable>
                <tfoot v-if="universities.length < 6">
                <tr>
                    <td colspan="2" style="text-align: right"></td>
                    <td colspan="2">
                        <VueMultiselect
                            :options="filteredOptions" v-model="university" label="name" track-by="id"
                            @select="add"
                            :disabled="universities.length >= 6"
                            :placeholder="$t('Add')"
                        />
                    </td>
                </tr>
                </tfoot>

            </table>

            <input-error :errors="errors"  />

            <table-bottom align="left">
                <button class="btn btn-primary">{{ $t('Save') }}</button>
            </table-bottom>
        </form>

    </AdminLayout>
</template>


<script>

import AdminLayout from "@/Layouts/AdminLayout.vue";
import TableBottom from "@/Components/TableBottom.vue";
import Field from "@/Components/Field.vue";
import InputError from "@/Components/InputError.vue";
import draggable from "vuedraggable";
import  VueMultiselect from "vue-multiselect";
import _intersection from "lodash/intersection";
export default {
    components: {InputError, Field, TableBottom, AdminLayout, draggable, VueMultiselect},
    props: {
        university_options: Array,
        items: Array,
    },
    data() {
        let $v = this;
        let universities = this.items.map(function (id) {
            return {
                id: id,
                name: $v.university_options.find(itm => itm.id == id)?.name
            };
        })

        return {
            university: null,
            universities: universities,
            filteredOptions: this.university_options.filter(itm => !$v.items.includes(itm.id)),
            errors: {}
        }
    },

    methods: {
        submit() {
            let items = this.universities.map(itm => itm.id);
            console.log(items);
            axios.put(route('lk.universities.update'), {items: items})
            .then(function(response){
                if (response.status === 200) {
                    alert("Список университетов сохранен");
                }
            }).catch(function (error) {
                var $v = this;
                if (error.response) {
                    if (error.response.data && error.response.data.errors && typeof error.response.data.errors == 'object') {
                        this.errors = error.response.data.errors;
                    }
                } else {
                    console.log(error, this);
                }
            });
        },
        add() {
            this.universities.push({
                id: this.university.id,
                name: this.university.name
            });
            this.university = null;
            this.updateFilteredOptions();
        },
        remove(index) {
            this.universities.splice(index, 1);
            this.updateFilteredOptions();
        },
        updateFilteredOptions(){
            let $v = this;
            this.filteredOptions = this.university_options.filter(itm => $v.universities.findIndex(itm1 => itm1.id == itm.id) === -1)
        }
    },

    created() {

    },


}

</script>

<style lang="scss">
@import "resources/css/admin-vars";
.table{
    td,th{
        &:first-child{
            width: 30px;
        }

        &.order{
            width: 140px;
            text-align: center;
        }
        &.remove {
            width: 50px;
            text-align: right;
        }

    }
    .handler {
        color: $dark-shadow-color;
        border-radius: 5px;
        font-size: 1.4em;
        padding: 5px;
        margin-left: -5px;
        cursor: grab;

        &:active {
            cursor: grabbing;
        }
    }
}
</style>
<style>
.ghost {
    border-color: #ccc;
    opacity: 0.5;
}
</style>

<i18n>
{
    "ru": {
        "Приоритет": "Приоритет",
        "Название вуза": "Название вуза",
        "Университеты": "Университеты",
        "HEADING1": "Приоритетные университеты для поступления",
        "HEADING2": "Выберите от 1 до 6 вузов-организаторов Олимпиады в порядке приоритета.",
        "Save": "Сохранить",
        "Add": "Добавить университет в список",
        "Список университетов сохранен": "Список университетов сохранен"
    },
    "en": {
        "Приоритет": "Priority",
        "Название вуза": "University name",
        "Университеты": "Universities",
        "HEADING1": "Priority universities for admission",
        "HEADING2": "Select from 1 to 6 universities hosting the Olympiad in order of priority.",
        "Save" : "Save",
        "Add": "Add University to my list",
        "Список университетов сохранен": "The list of universities has been saved"
    }
}

</i18n>
