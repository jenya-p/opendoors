<template>
    <AdminLayout :title=" $t('Профиль участника')">

        <form method="post" @submit.prevent="submit" class="block" v-field-container>


            <field for="locale" :label="$t('Язык участия в олимпиаде')">
                <VueMultiselect :options="locale_options" v-model="locale" label="name" track-by="id"
                                :allow-empty="false"/>
            </field>

            <field for="citizenship_id" :label="$t('Гражданство')">
                <VueMultiselect :options="citizenship_options" v-model="citizenship" :allow-empty="false" label="name"
                                track-by="id"/>
            </field>

            <h2>
                {{ $t('SUBTITLE_1') }}
            </h2>

            <field :errors="form.errors" for="first_name" :label="$t('Имя')"
                   :description="$t('Латиницей, как в документах')">
                <input type="text" class="input" v-model="form.first_name" :placeholder="$t('Имя')"
                       @keydown="strictLatin($event)"/>
            </field>

            <field :errors="form.errors" for="last_name" :label="$t('Фамилия')"
                   :description="$t('Латиницей, как в документах')">
                <input type="text" class="input" v-model="form.last_name" :placeholder="$t('Фамилия')"
                       @keydown="strictLatin($event)"/>
            </field>

            <field :errors="form.errors" for="birthdate" :label="$t('Дата рождения')">
                <input type="date" class="input" v-model="form.birthdate"/>
            </field>

            <field :errors="form.errors" for="sex" :label="$t('Пол')">
                <VueMultiselect :options="sex_options" v-model="sex" label="name"/>
            </field>

            <field :errors="form.errors" for="phone" :label="$t('Контактный телефон')">
                <phone-input v-model="form.phone" v-model:valid="phone_is_valid" class="input"/>
            </field>

            <field :errors="form.errors" for="email" :label="$t('Email')"
                   :description="$t('EMAIL_DESCRIPTION')">
                <input v-model="form.email" class="input" placeholder="Email"/>
            </field>

            <h2>
                {{ $t('SUBTITLE_2') }}
            </h2>
            <field :errors="form.errors" for="edu_level_ids.**" :label="$t('Уровень образования')"
                   class="field-checkboxes field-edu_level">
                <checkbox v-for="edu_level of edu_level_options" v-model="form.edu_level_ids"
                          :value="edu_level.id">
                    {{ edu_level.name }}
                </checkbox>
            </field>
            <h2>
                {{ $t('SUBTITLE_3') }}
            </h2>

            <template v-for="track of track_options">
                <field :label="track.name" class="field-profile" v-if="track.available">
                    <vue-multiselect
                        :options="profile_options"
                        :multiple="true"
                        v-model="profiles[track.id]"
                        @update:modelValue="profilesChange(track.id)"
                        label="name" track-by="id"/>
                </field>
            </template>
            <input-error :errors="form.errors" for="members.*"/>

            <table-bottom align="left">
                <button type="submit" class="btn btn-primary" :disabled="!formReady()">
                    {{ $t('Сохранить') }}
                </button>
                <p v-if="!formReady() && hint">{{ hint }}</p>
                <p v-else-if="$page.props.message" class="flesh-message">{{$page.props.message}}</p>
            </table-bottom>


        </form>


    </AdminLayout>
</template>

<script>
import {Link, useForm} from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Field from "@/Components/Field.vue";
import Checkbox from "@/Components/Checkbox.vue";
import VueMultiselect from "vue-multiselect";
import {selectable} from "@/Components/utils.js";

import TableBottom from "@/Components/TableBottom.vue";
import PhoneInput from "@/Components/PhoneInput.vue";
import InputError from "@/Components/InputError.vue";
import _isEmpty from "lodash/isEmpty.js";

export default {
    components: {InputError, PhoneInput, TableBottom, Checkbox, Field, VueMultiselect, AdminLayout},
    props: {
        edu_level_options: Array,
        citizenship_options: Array,
        locale_options: Array,
        sex_options: Array,
        track_options: Array,
        profile_options: Array,

        item: {
            type: Object,
            default: null
        }
    },

    data() {

        let profiles = {};
        for (const track of this.track_options) {
            profiles[track.id] = [];
            for (const member of this.item.members) {
                if (member.track_id == track.id) {
                    let index = this.profile_options.findIndex(itm => itm.id == member.profile_id);
                    if (index !== -1) {
                        profiles[track.id].push(this.profile_options[index]);
                    }
                }
            }
        }

        return {
            form: useForm(this.item),
            profiles: profiles,
            phone_is_valid: true,
        }
    },

    computed: {
        locale: selectable('locale_options', 'form', 'locale'),
        citizenship: selectable('citizenship_options', 'form', 'citizenship_id'),
        sex: selectable('sex_options', 'form', 'sex'),
    },
    methods: {
        submit() {
            let $v = this;
            this.form.put(route('lk.participant.update'), {
                preserveScroll: true,
                onSuccess: () => function(){
                    $v.updateAvailableTracks();
                }
            });
        },

        strictLatin(event) {
            var allowed = /[A-Za-z0-9\- '\.\,]/g;
            if ([27, 9, 18, 20, 16, 17, 13, 8, 46, 37, 39].indexOf(event.keyCode) != -1 || allowed.test(event.key)) {
                return true;
            } else {
                return event.preventDefault()
            }
        },
        formReady() {
            this.hint = null;
            if(!this.form.isDirty){
                return false;
            }
            try {

                if (
                    this.form.citizenship_id == null ||
                    this.form.locale == null ||
                    _isEmpty(this.form.first_name) ||
                    _isEmpty(this.form.last_name) ||
                    _isEmpty(this.form.birthdate) ||
                    _isEmpty(this.form.sex) ||
                    _isEmpty(this.form.phone) ||
                    _isEmpty(this.form.email)
                ) {
                    this.hint = this.$t('HINT_1');
                    return false;
                }

                if (this.form.edu_level_ids.length == 0) {
                    this.hint = this.$t('HINT_2');
                    return false;
                }

                if (this.form.members.length == 0) {
                    this.hint = this.$t('HINT_3');
                    return false;
                }


                this.hint = null;
                return true;
            } catch (e) {
                return false;
            }
        },

        profilesChange(trackId) {

            // Переносим данные о треках и профилях в form.members для отправки на сервер
            this.form.members.length = 0;
            for (const trackId in this.profiles) {
                for (const profile of this.profiles[trackId]) {
                    this.form.members.push({
                        track_id: trackId,
                        profile_id: profile.id
                    });
                }
            }
        },

        updateAvailableTracks() {
            this.availableTrackCount = this.track_options.length;
            for (const track of this.track_options) {
                track.available = true;
                for (const levelId of track.required_edu_level_ids) {
                    if (this.form.edu_level_ids.indexOf(levelId) == -1) {
                        this.profiles[track.id].length = 0;
                        track.available = false;
                        this.availableTrackCount--;
                        break;
                    }
                }
            }

        }

    },
    watch: {
        track_options(){
            this.updateAvailableTracks()
        },
        'form.edu_level_ids'() {
            this.updateAvailableTracks()
        },
        phone_is_valid(val) {
            if (val) {
                delete this.form.errors.phone;
            } else {
                this.form.errors.phone = this.$t('Поле содержит некорректный номер телефона');
            }
        },
    },
    created() {
        this.updateAvailableTracks()
    }

}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";

form.block:deep {

    p {
        margin: 0
    }

    &:not(.vertical) {
        h2 {
            margin-bottom: 2em;
            margin-top: 3em;
        }
    }

    &.vertical {
        h2 {
            margin-bottom: 1.5em;
            margin-top: 2.5em;
        }
    }


    a {
        color: $base-color;
        border-bottom: 1px solid $base-color;
        transition: color 200ms ease, border-bottom-color 200ms ease;

        &:hover {
            color: darken($base-color, 20);
            border-bottom-color: darken($base-color, 20);
        }
    }

    .disclaimer-ru {
        font-size: 1.4em;
        margin-top: 4em;
        margin-bottom: 15em;
    }

    .field {
        display: flex;
        width: 100%;
        margin-bottom: 2em;

        .input {
            font-family: sans-serif;
        }
        .iti{width: 100%}

        .input-description, .input-error {
            font-size: inherit;
        }

        &.field-checkboxes .field-inner,
        &.field-radios .field-inner {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        &:not(.field-checkboxes) {
            .input-label {

            }
        }

        @media screen and (min-width: 1500px) {
            .input-label + div {
                flex-grow: 1;
                display: flex;
                gap: 50px;
            }

            .input-description, .input-error {
                margin: 0;
            }
        }

        @media screen and (max-width: 1500px) {
            .input-label + div {
                display: block;
                width: 100%;
            }

            .input-description, .input-error {
                display: inline-block;
                margin-top: 0.6em;
            }
        }




        &.field-profile {
            .input-label + div {
                display: block;
                width: 100%;
            }

            .field-inner {
                width: 100%;
            }
        }
        @media screen and (min-width: 1500px) {
            &.field-email {
                align-items: center;
                .input-label {
                    margin: 0;
                }
            }
            .input-label + div {
                align-items: center;
            }

        }


    }

    :not(.vertical) {

        .input-description, .input-error {
            max-width: 450px;
        }

        .field-inner {
            width: 450px;
            flex-shrink: 0;
        }

        .input-label {
            width: 250px;
            flex-shrink: 0;
        }
    }


    &.vertical {
        flex-direction: column;
        gap: 0.8em;
        .input-label {
            padding: 0;
            width: 100%;
            margin-bottom: 1em;
            display: inline-block;
        }

        .field.field-edu_level {
            .input-label {
                display: none;
            }
        }
    }




}
.flesh-message{
    color: $success-color
}

</style>


<i18n>
{
    "ru": {
        "Профиль участника": "Профиль участника",
        "Регистрация участника олимпиады": "Регистрация участника олимпиады",
        "Язык участия в олимпиаде": "Язык участия в олимпиаде",
        "Гражданство": "Гражданство",
        "Латиницей, как в документах": "Латиницей, как в документах",
        "Имя": "Имя",
        "Фамилия": "Фамилия",
        "Дата рождения": "Дата рождения",
        "Пол": "Пол",
        "Контактный телефон": "Контактный телефон",
        "Email": "Email",
        "Пароль": "Пароль",
        "Подтверждение пароля": "Подтверждение пароля",
        "Повторите ввод пароля": "Повторите ввод пароля",
        "Олимпиада": "Олимпиада",
        "Сохранить": "Сохранить",
        "Поле содержит некорректный номер телефона": "Поле содержит некорректный номер телефона",
        "Уровень образования": "Уровень образования",
        "SUBTITLE_1": "Основная информация об участнике",
        "EMAIL_DESCRIPTION": "На указанный Email придёт запрос на подтверждение регистрации. Адрес электронной почты будет использоваться в качестве логина при входе.",
        "SUBTITLE_2": "Уровень (уровни) полученного или получаемого на данный момент образования",
        "SUBTITLE_3": "Треки и профили, по которым Вы хотите принять участие в олимпиаде",
        "CHECK_2": "Даю согласие на обработку персональных данных в соответствии с законодательством Российской Федерации",
        "CHECK_3": "Даю согласие на получение информационных рассылок",
        "HINT_1": "Заполните все поля",
        "HINT_2": "Укажите уровни образования",
        "HINT_3": "Выберите треки и профили для участия в олимпиаде"
    },
    "en": {
        "Профиль участника": "Participant's profile",
        "Регистрация участника олимпиады": "Registration of the Olympiad participant",
        "Язык участия в олимпиаде": "The language of participation in the Olympiad",
        "Гражданство": "Citizenship",
        "Латиницей, как в документах": "is in Latin, as in the documents",
        "Имя": "First Name",
        "Фамилия": "Last Name",
        "Дата рождения": "Date of birth",
        "Пол": "Sex",
        "Контактный телефон": "Contact phone number",
        "Email": "Email",
        "Пароль": "Password",
        "Подтверждение пароля": "Password confirmation",
        "Повторите ввод пароля": "Re-enter the password",
        "Олимпиада": "Olympiad",
        "Сохранить": "Save",
        "Поле содержит некорректный номер телефона": "The field contains an invalid phone number",
        "Уровень образования": "The level of education",
        "SUBTITLE_1": "Basic information about the participant",
        "EMAIL_DESCRIPTION": "A request for confirmation of registration will be sent to the specified Email address. The email address will be used as the login when logging in.",
        "SUBTITLE_2": "Level(s) of education you have received or are currently receiving",
        "SUBTITLE_3": "Tracks and profiles that you want to participate in the Olympiad on",
        "CHECK_2": "I give my consent to the processing of personal data in accordance with the legislation of the Russian Federation",
        "CHECK_3": "I give my consent to receive newsletters",
        "HINT_1": "Fill in all fields",
        "HINT_2": "Specify the levels of education",
        "HINT_3": "Select tracks and profiles to participate in the Olympiad"
    }
}


</i18n>
