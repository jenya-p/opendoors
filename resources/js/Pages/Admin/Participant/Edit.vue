<template>
    <AdminLayout :title="item ? ('Участник ' + item.user.name) : 'Новый участник'"
                 :breadcrumb="[{link: route('admin.participant.index'), label: 'Участники'}, item ? item.user.name : 'Новый участник']">

        <div class="block-wrapper">
        <form method="post" @submit.prevent="submit" class="block" v-field-container>
            <h2>Карточка участника</h2>

            <field label="Аккаунт" class="field-display" v-if="item">
                <Link :href="route('admin.user.edit', {user: item.user.id})">{{item.user.name}} ({{item.user.email}})</Link>
            </field>
            <field label="Аккаунт" v-else :errors="form.errors" for="user_id">
                <UserSelect v-model="user" />
            </field>

            <field :errors="form.errors" for="locale" label="Язык участия в олимпиаде">
                <VueMultiselect :options="locale_options" v-model="locale" label="name" track-by="id"/>
            </field>

            <field :errors="form.errors" for="citizenship_id" label="Гражданство">
                <VueMultiselect :options="citizenship_options" v-model="citizenship" label="name" track-by="id"/>
            </field>

            <field :errors="form.errors" for="first_name" label="Имя">
                <input class="input" v-model="form.first_name" />
            </field>

            <field :errors="form.errors" for="last_name" label="Фамилия">
                <input class="input" v-model="form.last_name" />
            </field>

            <field :errors="form.errors" for="sex" label="Пол">
                <VueMultiselect :options="sex_options" v-model="sex" label="name"/>
            </field>

            <field :errors="form.errors" for="phone" label="Контактный телефон">
                <input class="input" v-model="form.phone" />
            </field>

            <field :errors="form.errors" for="name" label="Дата рождения">
                <input type="date" class="input" v-model="form.birthdate" />
            </field>

            <field :errors="form.errors" for="required_edu_level_ids.**" label="Уровни образования" class="field-checkboxes">
                <checkbox v-for="eduLevel of edu_level_options" v-model="form.edu_level_ids" :value="eduLevel.id">{{eduLevel.name}}</checkbox>
            </field>

            <div class="block-footer">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <!--                <History type="user"/>-->
            </div>
        </form>
        </div>
    </AdminLayout>
</template>

<script>
import {Link, useForm} from '@inertiajs/vue3';
import Authenticated from '@/Layouts/AdminLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Field from "@/Components/Field.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Attachments from "@/Components/Attachments.vue";
import UserSelect from "@/Components/UserSelect.vue";
import VueMultiselect from "vue-multiselect";
import {selectable} from "@/Components/utils.js";


export default {
    components: {VueMultiselect, UserSelect, Attachments, Checkbox, TextareaAutosize, AdminLayout, Field, Authenticated, Link},
    props: {
        edu_level_options: Array,
        citizenship_options: Array,
        sex_options: Array,
        locale_options: Array,
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            form: useForm(this.item ? this.item : {
                user_id: null,
                locale: null,
                citizenship_id: null,
                first_name: '',
                last_name: '',
                phone: '',
                sex: null,
                birthdate: '',
                edu_level_ids: []
            }),
            user: this.item ? this.item.user : null,
        }
    },


    methods: {
        submit() {
            if (this.item) {
                this.form.put(route('admin.participant.update', {participant: this.item.id}));
            } else {
                this.form.post(route('admin.participant.store'));
            }
        }
    },

    watch: {
        'user'(){
            if(this.user){
                this.form.user_id = this.user.id
            } else {
                this.form.user_id = null
            }
        },
    },
    computed: {
        citizenship: selectable('citizenship_options', 'form', 'citizenship_id'),
        sex: selectable('sex_options', 'form', 'sex'),
        locale: selectable('locale_options', 'form', 'locale'),
    }


}
</script>

<style lang="scss" scoped>

.text-center{text-align: center}
.field-checkboxes:deep{
    .field-inner {
        flex-direction: column;
    }
}

</style>

