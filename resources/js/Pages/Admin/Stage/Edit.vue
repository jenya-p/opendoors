<template>
    <AdminLayout :title="item.id ? 'Этап' : 'Новый трек'"
                 :breadcrumb="[{link: route('admin.stage.index'), label: 'Этапы'}, item.id ? item.type_name: 'Новый']">


            <form method="post" @submit.prevent="submit" class="block" v-field-container>
                <h2>Основная информация</h2>
                <field :errors="form.errors" for="profile_id" label="Профиль" class="field-display">
                    {{item.profile.name}}
                </field>

                <field :errors="form.errors" for="track_id" label="Трек" class="field-display">
                    {{item.track.name}}
                </field>

                <field :errors="form.errors" for="type_id" label="Тип этапа"  class="field-display">
                    {{item.type_name}}
                </field>


                <field :errors="form.errors" for="settings" label="Настройки этапа" >

                </field>

                <div class="block-footer">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <!--                <History type="user"/>-->
                </div>

            </form>



    </AdminLayout>
</template>
<script>
import {useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import _extend from "lodash/extend";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import VueMultiselect from "vue-multiselect";
export default {
    components: {
        VueMultiselect,
        TextareaAutosize,
        AdminLayout,
        Field
    },
    props: {
        profile_options: Array,
        track_options: Array,
        type_options: Array,
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        if (this.item.description === null) {
            this.item.description = '';
        }
        return {
            form: useForm(_extend({
                name: null,
                name_en: null,
                url: null
            }, this.item)),
            tabErrors: {
                info: false, questions: false
            }
        }
    },
    watch: {

    },

    methods: {
        submit() {
            this.form.errors = [];
            if (this.form.questions) {
                for (const index in this.form.questions) {
                    this.form.questions[index].order = parseInt(index) + 1;
                }
            }
            if (this.item.id) {
                this.form.put(route('admin.track.update', {track: this.item.id}));
            } else {
                this.form.post(route('admin.track.store'));
            }
        }
    },

    computed: {
        profile: {
            get() {
                return this.profile_options.find(itm => itm.id == this.form.profile_id);
            },
            set(value) {
                this.form.profile_id = value.id;
            }
        },
        track: {
            get() {
                return this.track_options.find(itm => itm.id == this.form.track_id);
            },
            set(value) {
                this.form.track_id = value.id;
            }
        },
        type: {
            get() {
                return this.type_options.find(itm => itm.id == this.form.type);
            },
            set(value) {
                this.form.type = value.id;
            }
        }
    }



}
</script>
<style lang="scss" scoped>

</style>

