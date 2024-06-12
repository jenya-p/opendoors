<template>
    <AdminLayout :title="item ? ('Соискатель ' + item.user.name) : 'Новый соискатель'"
                 :breadcrumb="[{link: route('admin.student.index'), label: 'Соискатели'}, item ? item.user.name : 'Новый соискатель']">

        <div class="block-wrapper">
        <form method="post" @submit.prevent="submit" class="block" v-field-container>
            <h2>Карточка соискателя</h2>

            <field label="Аккаунт" class="field-display" v-if="item">
                <Link :href="route('admin.user.edit', {user: item.user.id})">{{item.user.name}} ({{item.user.email}})</Link>
            </field>
            <field label="Аккаунт" v-else :errors="form.errors" for="user_id">
                <UserSelect v-model="user" />
            </field>

            <field label="Уровень образования" :errors="form.errors" for="edu_level_id">
                <VueMultiselect
                    class="university-select"
                    ref="multiselect"
                    placeholder="Название"
                    selectLabel="выбрано"
                    selectedLabel="Enter что бы выбрать"
                    :taggable="false"
                    :searchable="true"
                    v-model="edu_level"
                    :options="edu_level_options"
                    track-by="id"
                    label="name"
                    :internal-search="true"
                    :allow-empty="false" />
            </field>

            <field label="Гражданство" :errors="form.errors" for="citizenship">
                <input type="text" class="input" v-model="form.citizenship">
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
    components: {VueMultiselect, UserSelect, Attachments, Checkbox, TextareaAutosize, AdminLayout, Field, Authenticated, Link},
    props: {
        edu_level_options: Array,
        track_options: Array,
        profile_options: Array,
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            form: useForm(this.item ? this.item : {
                user_id: null,
                edu_level_id: null,
                profiles: [],
                citizenship: '',
            }),
            user: this.item ? this.item.user : null,
            edu_level: this.item ? this.item.edu_level : null,
            profiles: []
        }
    },


    methods: {
        submit() {
            if (this.item) {
                this.form.put(route('admin.student.update', {student: this.item.id}));
            } else {
                this.form.post(route('admin.student.store'));
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
        'edu_level'(){
            if(this.user){
                this.form.edu_level_id = this.edu_level.id
            } else {
                this.form.edu_level_id = null
            }
        }
    }


}
</script>

<style lang="scss" scoped>

.text-center{text-align: center}


</style>

