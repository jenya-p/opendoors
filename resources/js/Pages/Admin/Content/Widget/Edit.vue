<script>
import {useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";

export default {
    components: {TextareaAutosize, AdminLayout, Field},
    props: {
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            form: useForm(this.item ? this.item : {
                data: 'asdasd',
            }),
        }
    },

    computed: {
      date: {
          // get(){
          //     return JSON.stringify(this.form.data);
          // }, set(v){
          //     this.form.data = JSON.parse(v);
          // }
      }
    },

    methods: {
        submit() {
            if (this.item) {
                this.form.put(route('admin.widget.update', {widget: this.item.id}));
            } else {
                this.form.post(route('admin.widget.store'));
            }
        }
    }

}
</script>

<template>
    <AdminLayout :title="item ? item.name : 'Новая страница'"
                 :breadcrumb="[{link: route('admin.widget.index'), label: 'Виджеты'}, item ? item.name: 'Новая страница']">

            <form method="post" @submit.prevent="submit" class="block" v-field-container>
                <h2>Основная информация</h2>

                <field :errors="form.errors" for="data">
                    {{item.data}}
                </field>
                <div class="block-footer">
                    <button type="submit" class="btn btn-primary" disabled>Сохранить</button>
                    <!--                <History type="user"/>-->
                </div>

            </form>

    </AdminLayout>
</template>

<style lang="scss" scoped>

</style>

