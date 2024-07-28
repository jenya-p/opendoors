<template>
    <form action="/register" @submit="submit" method="post" class="block" v-field-container>
        <input type="hidden" name="_token" :value="csrf">
        <h1>{{ $t('Регистрация участника олимпиады') }}</h1>
        <field for="first_name" :label="$t('Язык участия в олимпиаде')">
            <VueMultiselect :options="locale_options" v-model="locale" label="name" track-by="id"/>
        </field>

        <field for="first_name" :label="$t('Гражданство')">
            <VueMultiselect :options="citizenship_options" v-model="citizenship" label="name" track-by="id"/>
        </field>

        <template v-if="citizenship">
            <transition type="fade">
                <p class="disclaimer-ru" v-show="citizenship.code == 'RUS'">
                    <template v-if="$i18n.locale=='ru'">Согласно <a href="/rules">Правилам Олимпиады</a>, участие доступно только гражданам иностранных государств.</template>
                    <template v-else>According to the <a href="/rules">rules of the Olympiad</a>, participation is available only to citizens of foreign countries.</template>
                </p>
            </transition>
            <transition type="fade">
                <div v-show="citizenship.code != 'RUS'">
                    <h2>{{$t('SUBTITLE_1')}}</h2>

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

                    <field :errors="form.errors" for="birthday" :label="$t('Дата рождения')">
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

                    <field :errors="form.errors" for="password" :label="$t('Пароль')">
                        <input type="password" v-model="form.password" class="input" :placeholder="$t('Пароль')"/>
                    </field>

                    <field :errors="form.errors" for="confirm" :label="$t('Подтверждение пароля')">
                        <input type="password" v-model="form.confirm" class="input"
                               :placeholder="$t('Повторите ввод пароля')"/>
                    </field>

                    <h2>{{$t('SUBTITLE_2')}}</h2>

                    <field :errors="form.errors" for="edu_level" :label="$t('Уровень образования')" class="field-checkboxes">
                        <checkbox v-for="edu_level of edu_level_options" v-model="form.edu_levels"
                                  :value="edu_level.id">
                            {{ edu_level.name }}
                        </checkbox>
                    </field>

                    <h2 style="margin-bottom: 1.5em">{{$t('Олимпиада')}}</h2>
                    <p>
                        {{$t('SUBTITLE_3')}}
                    </p>
                    <field v-for="track of track_options" :label="track.name" class="field-profile">
                        <vue-multiselect :options="profile_options"
                                         :multiple="true"
                                         v-model="profiles[track.id]"
                                         label="name" track-by="id"/>
                    </field>


                    <field label="" class="field-checkboxes">
                        <checkbox v-model="accept1">
                            <template v-if="$i18n.locale=='ru'">Принимаю условия <a href="/rules">Правил Олимпиады</a></template>
                            <template v-else>I accept the terms <a href="/rules">Rules of the Olympiad</a></template>
                        </checkbox>
                        <checkbox v-model="accept2">{{$t('CHECK_2')}}</checkbox>
                        <checkbox v-model="accept3">{{$t('CHECK_3')}}</checkbox>
                    </field>
                    <div style="display: flex; align-items: baseline; gap: 2em; margin-top: 4em">
                        <button type="submit" class="btn btn-primary" :disabled="!formReady()">{{$t('Зарегистрироваться')}}
                        </button>
                        <p v-if="!formReady() && hint">{{ hint }}</p>
                    </div>

                </div>
            </transition>


        </template>


    </form>
</template>


<script>
import Field from "@/Components/Field.vue";
import {selectable} from "@/Components/utils.js";
import VueMultiselect from 'vue-multiselect';
import {useForm} from "@inertiajs/vue3";
import Checkbox from "@/Components/Checkbox.vue";
import TableBottom from "@/Components/TableBottom.vue";
import PhoneInput from "@/Components/PhoneInput.vue";
import _extend from "lodash/extend";
import _isEmpty from "lodash/isEmpty";

export default {
    name: 'RegistrationForm',
    components: {PhoneInput, TableBottom, Checkbox, Field, VueMultiselect},
    data() {
        let data = {};
        try {
            data = JSON.parse(localStorage.getItem('participant_registration'));
        } catch (e) {
        }

        data = _extend({
            locale: 'ru',
            citizenship_id: null,
            last_name: '',
            first_name: '',
            sex: null,
            birthdate: null,
            phone: null,
            email: '',
            password: '',
            confirm: '',
            edu_levels: [],
            membership: [],
        }, data);


        return {
            form: useForm(data),
            phone_is_valid: true,
            edu_level_options: [],
            citizenship_options: [],
            locale_options: [],
            sex_options: [],
            track_options: [],
            profile_options: [],
            profiles: {},
            accept1: false,
            accept2: false,
            accept3: false,
            hint: null,
            csrf: null,
        }

    },
    computed: {
        locale: selectable('locale_options', 'form', 'locale'),
        citizenship: selectable('citizenship_options', 'form', 'citizenship_id'),
        sex: selectable('sex_options', 'form', 'sex'),

    },
    methods: {
        strictLatin(event) {
            var allowed = /[A-Za-z0-9\- '\.\,]/g;
            if ([27, 9, 18, 20, 16, 17, 13, 8, 46, 37, 39].indexOf(event.keyCode) != -1 || allowed.test(event.key)) {
                return true;
            } else {
                return event.preventDefault()
            }
        },
        formReady() {
            try {

                if (
                    this.form.citizenship_id == null ||
                    this.form.locale == null ||
                    _isEmpty(this.form.first_name) ||
                    _isEmpty(this.form.last_name) ||
                    _isEmpty(this.form.birthdate) ||
                    _isEmpty(this.form.sex) ||
                    _isEmpty(this.form.phone) ||
                    _isEmpty(this.form.email) ||
                    _isEmpty(this.form.password) ||
                    _isEmpty(this.form.confirm)
                ) {
                    this.hint = this.$t('HINT_1');
                    return false;
                }

                if (this.form.edu_levels.length == 0) {
                    this.hint = this.$t('HINT_2');
                    return false;
                }

                if (this.form.membership.length == 0) {
                    this.hint = this.$t('HINT_3');
                    return false;
                }

                if (!this.accept1 || !this.accept2 || !this.accept3) {
                    this.hint = this.$t('HINT_4');
                    return false;
                }

                this.hint = null;
                return true;
            } catch (e) {
                console.error(e);
                this.hint = null;
                return false;
            }

        },
        submit() {
            console.log('submit');
            if (!this.formReady()) {
                return false;
            }
        }


    },
    watch: {
        form: {
            deep: true,
            handler() {
                localStorage.setItem('participant_registration', JSON.stringify(this.form.data()));
            }
        },
        phone_is_valid(val) {
            if (val) {
                delete this.form.errors.phone;
            } else {
                this.form.errors.phone = this.$t('Поле содержит некорректный номер телефона');
            }
        },
        profiles: {
            deep: true,
            handler() {
                this.form.membership.length = 0;
                for (const trackId in this.profiles) {
                    for (const profile of this.profiles[trackId]) {
                        this.form.membership.push({
                            track_id: trackId,
                            profile_id: profile.id
                        });
                    }
                }
            }
        }
    },
    mounted() {
        this.edu_level_options = JSON.parse(this.$el.parentElement.getAttribute('edu_level_options'));
        this.citizenship_options = JSON.parse(this.$el.parentElement.getAttribute('citizenship_options'));
        this.locale_options = JSON.parse(this.$el.parentElement.getAttribute('locale_options'));
        this.sex_options = JSON.parse(this.$el.parentElement.getAttribute('sex_options'));

        this.track_options = JSON.parse(this.$el.parentElement.getAttribute('track_options'));
        this.profile_options = JSON.parse(this.$el.parentElement.getAttribute('profile_options'));
        this.csrf = this.$el.parentElement.getAttribute('csrf');

        this.profiles = {};
        for (const track of this.track_options) {
            this.profiles[track.id] = [];
            for (const membership of this.form.membership) {
                if(membership.track_id == track.id){
                    let index = this.profile_options.findIndex(itm => itm.id == membership.profile_id);
                    this.profiles[track.id].push(this.profile_options[index]);
                }
            }
        }

    }

}
</script>


<style lang="scss" scoped>

@import "resources/css/admin-vars";

form.block {
    h2 {
        margin-bottom: 2.5em;
        margin-top: 4em;


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

    &:deep(.field) {
        display: flex;
        width: 100%;
        margin-bottom: 2em;


        .input {
            font-family: sans-serif;
        }


        .field-inner {
            width: 500px;
            flex-shrink: 0;
        }

        .input-label {
            width: 250px;
            flex-shrink: 0;
        }

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
            .input-label, .input-description, .input-error {
                margin: 0;
                padding-top: 15px;
            }
        }

        .input-label + div {
            flex-grow: 1;
            display: flex;
            gap: 50px;
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
    }
}
</style>

<i18n>
{
    "ru": {

        "SUBTITLE_1":           "Пожалуйста, введите достоверные данные, иначе результаты Олимпиады могут быть не зачтены",
        "EMAIL_DESCRIPTION":    "На указанный Email придёт запрос на подтверждение регистрации. Адрес электронной почты будет использоваться в качестве логина при входе.",
        "SUBTITLE_2":           "Выберите уровень (уровни) полученного или получаемого на данный момент образования",
        "SUBTITLE_3":           "Выберите треки и профили, по которым хотите принять участие в олимпиаде",

        "CHECK_2":              "Даю согласие на обработку персональных данных в соответствии с законодательством Российской Федерации",
        "CHECK_3":              "Даю согласие на получение информационных рассылок",
        "HINT_1":               "Заполните все поля регистрационной формы",
        "HINT_2":               "Укажите уровни образования",
        "HINT_3":               "Выберите треки и профили для участия в олимпиаде",
        "HINT_4":               "Подтвердите согласие с правилами олимпиады, согласие на обработку персональных данных и получение информационных рассылок"

    },
    "en": {
        "Регистрация участника олимпиады": "Registration of the Olympiad participant",
        "Язык участия в олимпиаде": "The language of participation in the Olympiad",
        "Гражданство":  "Citizenship",
        "Латиницей, как в документах":  "is in Latin, as in the documents",
        "Имя": "First Name",
        "Фамилия":  "Last Name",
        "Дата рождения": "Date of birth",
        "Пол":  "Paul",
        "Контактный телефон": "Contact phone number",
        "Email":  "Email",
        "Пароль":  "Password",
        "Подтверждение пароля":  "Password confirmation",
        "Повторите ввод пароля": "Re-enter the password",
        "Олимпиада": "Olympiad",
        "Зарегистрироваться": "Register",
        "Поле содержит некорректный номер телефона":  "The field contains an invalid phone number",
        "Уровень образования": "The level of education",


        "SUBTITLE_1":         "Please enter reliable data, otherwise the results of the Olympiad may not be counted",
        "EMAIL_DESCRIPTION":  "A request for confirmation of registration will be sent to the specified Email address. The email address will be used as the login when logging in.",
        "SUBTITLE_2":         "Select the level(s) of education you have received or are currently receiving",
        "SUBTITLE_3":         "Select the tracks and profiles that you want to participate in the Olympiad on",

        "CHECK_2":            "I give my consent to the processing of personal data in accordance with the legislation of the Russian Federation",
        "CHECK_3":            "I give my consent to receive newsletters",
        "HINT_1":             "Fill in all fields of the registration form",
        "HINT_2":             "Specify the levels of education",
        "HINT_3":             "Select tracks and profiles to participate in the Olympiad",
        "HINT_4":             "Confirm your agreement with the rules of the Olympiad, consent to the processing of personal data and receipt of newsletters"

    }
}


</i18n>
