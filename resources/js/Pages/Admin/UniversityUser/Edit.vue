<template>
    <AdminLayout :title="item ? ('Представитель ВУЗ ' + item.user.display_name) : 'Новый представитель ВУЗ'"
                 :breadcrumb="[{link: route('admin.university-user.index'), label: 'Представители ВУЗ'}, item ? item.user.name : 'Новый представитель']">

        <div class="block-wrapper">
        <form method="post" @submit.prevent="submit" class="block" v-field-container>
            <h2>Карточка представителя</h2>

            <field label="Аккаунт" class="field-display" v-if="item">
                <Link :href="route('admin.user.edit', {user: item.user.id})">{{item.user.name}} ({{item.user.email}})</Link>
            </field>
            <field label="Аккаунт" v-else :errors="form.errors" for="user_id">
                <UserSelect v-model="user" />
            </field>


            <field label="Университет" class="field-display" v-if="item">
                <Link :href="route('admin.university.edit', {university: item.university.id})">{{item.university.name}}</Link>
            </field>
            <field label="Университет" v-else :errors="form.errors" for="university_id">
                <VueMultiselect
                    class="university-select"
                    ref="multiselect"
                    placeholder="Название университета"
                    selectLabel="выбрано"
                    selectedLabel="Enter что бы выбрать"
                    :taggable="false"
                    :searchable="true"
                    v-model="university"
                    :options="university_options"
                    track-by="id"
                    label="name"
                    :internal-search="true"
                    :allow-empty="false" />
            </field>


            <field :errors="form.errors" for="roles" label="Роли" class="field-checkboxes">
                <checkbox v-model="form.roles" v-for="(name, key) of role_options" :value="key" >{{name}}</checkbox>
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

export default {
    components: {UserSelect, VueMultiselect, Attachments, Checkbox, TextareaAutosize, AdminLayout, Field, Authenticated, Link},
    props: {
        role_options: Array,
        university_options: Array,
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            form: useForm(this.item ? this.item : {
                user_id: null,
                university_id: null,
                roles: [],
            }),
            user: this.item ? this.item.user : null,
            university: this.item ? this.item.university : null,
        }
    },


    methods: {
        submit() {
            if (this.item) {
                this.form.put(route('admin.university-user.update', {university_user: this.item.id}));
            } else {
                this.form.post(route('admin.university-user.store'));
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
        'university'(){
            if(this.university){
                this.form.university_id = this.university.id
            } else {
                this.form.university_id = null
            }
        }
    }

}
</script>

<style lang="scss" scoped>




</style>

