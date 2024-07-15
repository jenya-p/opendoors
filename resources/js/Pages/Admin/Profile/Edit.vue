<template>
    <AdminLayout :title="item.id ? item.name : 'Новый профиль'"
                 :breadcrumb="[{link: route('admin.profile.index'), label: 'Профили'}, item.id ? item.name: 'Новый']">

        <form method="post" @submit.prevent="submit" class="block" v-field-container>
            <tabs>
                <tab label="Основная информация" name="main">
                    <field :errors="form.errors" for="name" label="Название">
                        <input class="input" v-model="form.name"/>
                    </field>

                    <field :errors="form.errors" for="title" label="Название (EN)">
                        <input class="input" v-model="form.name_en"/>
                    </field>

                </tab>
                <tab label="Файлы" name="files">
                    <table class="table" v-for="(fileTypeTypeName, fileTypeType) of file_type_types">
                        <tr>
                            <th class="type">
                                {{ fileTypeTypeName }}
                            </th>
                            <th class="files">
                                Рус
                            </th>
                            <th class="files">
                                Англ
                            </th>
                            <th class="status">
                                Активно
                            </th>
                        </tr>
                        <template v-for="file of files">
                            <tr v-if="file.type == fileTypeType">
                                <td class="type">
                                    {{ file.name }}
                                    <div class="track">
                                        {{ file.track_name }}
                                    </div>
                                </td>

                                <td class="files">
                                    <attachment v-model:item="file.file" item_type="profile_file" :item_id="file.id" title=""
                                                type="ru"/>
                                </td>
                                <td class="files">
                                    <attachment v-model:item="file.file_en" item_type="profile_file" :item_id="file.id"
                                                title="" type="en"/>
                                </td>
                                <td class="status">
                                    <checkbox v-model="file.active">Доступно</checkbox>
                                </td>
                            </tr>
                        </template>
                    </table>

                </tab>
            </tabs>

            <table-bottom align="left">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <!--                <History type="user"/>-->
            </table-bottom>

        </form>


    </AdminLayout>
</template>
<script>
import {useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import _extend from "lodash/extend";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import Tabs from "@/Components/tabs.vue";
import Tab from "@/Components/tab.vue";
import Attachment from "@/Components/Attachment.vue";
import TableBottom from "@/Components/TableBottom.vue";
import Checkbox from "@/Components/Checkbox.vue";

export default {
    components: {
        Checkbox,
        TableBottom,
        Attachment,
        Tabs, Tab,
        TextareaAutosize,
        AdminLayout,
        Field
    },
    props: {
        item: {
            type: Object,
            default: null
        },
        file_types: Array,
        file_type_types: Object
    },

    data() {
        if (this.item.description === null) {
            this.item.description = '';
        }
        let $v = this;
        return {
            form: useForm(_extend({
                name: null,
                name_en: null,
                url: null,
                files: []
            }, this.item)),
            files: this.file_types.map(function (itm) {
                let file = $v.item.files.find(fl => fl.type_id == itm.id);
                if (file) {
                    file.type = itm.type;
                    file.name = itm.name;
                    file.track_name = itm.track?.name;
                    file.active = file.status == 'active';
                } else {
                    file = {
                        id: null,
                        type: itm.type,
                        name: itm.name,
                        track_name: itm.track?.name,
                        type_id: itm.id,
                        active: false,
                        file: null,
                        file_en: null,
                    }
                }

                return file;
            }),
            tabErrors: {
                info: false, questions: false
            }
        }
    },
    watch: {},

    methods: {
        submit() {
            this.form.files = this.files.map(function(itm){
                return {
                    id: itm.id,
                    type_id: itm.type_id,
                    file_id: itm.file?.id,
                    file_en_id: itm.file_en?.id,
                    active: itm.active
                }
            });

            this.form.errors = [];
            if (this.item.id) {
                this.form.put(route('admin.profile.update', {profile: this.item.id}));
            } else {
                this.form.post(route('admin.profile.store'));
            }
        }
    }

}
</script>
<style lang="scss" scoped>
.table {
    margin: 1em 0 3em;

    th.type {
        font-weight: 400;
        font-size: 18px;
        color: #0c6e9b;
    }

    td {
        vertical-align: top;
        padding-top: 25px;

        &.type {
            width: 300px
        }

        .track {
            margin-top: 0.5em;
            font-size: 0.8em
        }

        &.files {
            width: 140px;
            padding-top: 10px
        }

        &.status {
        }
    }
}
</style>

