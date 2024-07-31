<template>
    <form action="/register" @submit.prevent.stop="submit" method="post" class="block">
        <input type="hidden" name="_token" :value="csrf">
        <h1>{{ $t('Регистрация участника олимпиады') }}</h1>
        <field for="locale" :label="$t('Язык участия в олимпиаде')">
            <VueMultiselect :options="locale_options" v-model="locale" label="name" track-by="id"/>
        </field>

        <field for="citizenship_id" :label="$t('Гражданство')">
            <VueMultiselect :options="citizenship_options" v-model="citizenship" label="name" track-by="id"/>
        </field>

        <template v-if="citizenship">
            <transition type="fade">
                <p class="disclaimer-ru" v-show="citizenship.code == 'RUS'">
                    <template v-if="$i18n.locale=='ru'">Согласно <a href="/rules" target="_blank">Правилам Олимпиады</a>, участие
                        доступно только гражданам иностранных государств.
                    </template>
                    <template v-else>According to the <a href="/rules" target="_blank">rules of the Olympiad</a>, participation is
                        available only to citizens of foreign countries.
                    </template>
                </p>
            </transition>
            <transition type="fade">
                <div v-show="citizenship.code != 'RUS'">
                    <h2>{{ $t('SUBTITLE_1') }}</h2>

                    <field :errors="errors" for="first_name" :label="$t('Имя')"
                           :description="$t('Латиницей, как в документах')">
                        <input type="text" class="input" v-model="form.first_name" :placeholder="$t('Имя')"
                               @keydown="strictLatin($event)"/>
                    </field>

                    <field :errors="errors" for="last_name" :label="$t('Фамилия')"
                           :description="$t('Латиницей, как в документах')">
                        <input type="text" class="input" v-model="form.last_name" :placeholder="$t('Фамилия')"
                               @keydown="strictLatin($event)"/>
                    </field>

                    <field :errors="errors" for="birthdate" :label="$t('Дата рождения')">
                        <input type="date" class="input" v-model="form.birthdate"/>
                    </field>

                    <field :errors="errors" for="sex" :label="$t('Пол')">
                        <VueMultiselect :options="sex_options" v-model="sex" label="name"/>
                    </field>

                    <field :errors="errors" for="phone" :label="$t('Контактный телефон')">
                        <phone-input v-model="form.phone" v-model:valid="phone_is_valid" class="input"/>
                    </field>

                    <field :errors="errors" for="email" :label="$t('Email')"
                           :description="$t('EMAIL_DESCRIPTION')">
                        <input v-model="form.email" class="input" placeholder="Email"/>
                    </field>

                    <field :errors="errors" for="password" :label="$t('Пароль')">
                        <input type="password" v-model="form.password" class="input" :placeholder="$t('Пароль')"/>
                    </field>

                    <field :errors="errors" for="password_confirmation" :label="$t('Подтверждение пароля')">
                        <input type="password" v-model="form.password_confirmation" class="input"
                               :placeholder="$t('Повторите ввод пароля')"/>
                    </field>

                    <h2>{{ $t('SUBTITLE_2') }}</h2>

                    <field :errors="errors" for="edu_level_ids.**" :label="$t('Уровень образования')"
                           class="field-checkboxes field-edu_level">
                        <checkbox v-for="edu_level of edu_level_options" v-model="form.edu_level_ids"
                                  :value="edu_level.id">
                            {{ edu_level.name }}
                        </checkbox>
                    </field>

                    <template v-if="availableTrackCount >0">
                        <!--                    <h2 style="margin-bottom: 1.5em">{{$t('Олимпиада')}}</h2>-->
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
                        <input-error :errors="errors" for="members.*" />

                        <field label="" class="field-checkboxes field-jurisms">
                            <checkbox v-model="accept1">
                                <template v-if="$i18n.locale=='ru'">Принимаю условия <a href="/rules" target="_blank">Правил Олимпиады</a>
                                </template>
                                <template v-else>I accept the terms <a href="/rules" target="_blank">Rules of the Olympiad</a></template>
                            </checkbox>
                            <checkbox v-model="accept2">{{ $t('CHECK_2') }}</checkbox>
                            <checkbox v-model="accept3">{{ $t('CHECK_3') }}</checkbox>
                        </field>

                        <div class="button-wrp">
                            <button type="submit" class="btn btn-primary" :disabled="!formReady()">
                                {{ $t('Зарегистрироваться') }}
                            </button>
                            <p v-if="!formReady() && hint">{{ hint }}</p>
                        </div>
                    </template>
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
import InputError from "@/Components/InputError.vue";

export default {
    name: 'RegistrationForm',
    components: {InputError, PhoneInput, TableBottom, Checkbox, Field, VueMultiselect},
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
            password_confirmation: '',
            edu_level_ids: [],
            members: [],
        }, data);


        return {
            form: data,
            errors: {},
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
            availableTrackCount: 0,
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
                    _isEmpty(this.form.password_confirmation)
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

                if (!this.accept1 || !this.accept2 || !this.accept3) {
                    this.hint = this.$t('HINT_4');
                    return false;
                }

                this.hint = null;
                return true;
            } catch (e) {
                this.hint = null;
                return false;
            }
        },
        submit(event) {
            event.preventDefault();
            if (!this.formReady()) {
                return false;
            }
            var $v = this;
            axios.post('/register', this.form).then(function(resp){
                document.location = '/lk';
            }).catch(function(error){
                if (error.response) {
                    if (error.response.data && error.response.data.errors && typeof error.response.data.errors == 'object') {
                        $v.errors = error.response.data.errors;
                    }
                } else {
                    console.log(error, this);
                }
            });

        },

        profilesChange(trackId){

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

        updateAvailableTracks(){
            this.availableTrackCount = this.track_options.length;
            for (const track of this.track_options) {
                track.available = true;
                for (const levelId of track.required_edu_level_ids) {
                    if (this.form.edu_level_ids.indexOf(levelId) == -1) {
                        this.profiles[track.id].length = 0;
                        track.available = false;
                        this.availableTrackCount --;
                        break;
                    }
                }
            }

        }

    },
    watch: {
        form: {
            deep: true,
            handler() {
                localStorage.setItem('participant_registration', JSON.stringify(this.form));
            }
        },
        'form.edu_level_ids'() {
            this.updateAvailableTracks()
        },
        phone_is_valid(val) {
            if (val) {
                delete this.errors.phone;
            } else {
                this.errors.phone = this.$t('Поле содержит некорректный номер телефона');
            }
        },
    },
    mounted() {
        this.edu_level_options = JSON.parse(this.$el.parentElement.getAttribute('edu_level_options'));
        this.citizenship_options = JSON.parse(this.$el.parentElement.getAttribute('citizenship_options'));
        this.locale_options = JSON.parse(this.$el.parentElement.getAttribute('locale_options'));
        this.sex_options = JSON.parse(this.$el.parentElement.getAttribute('sex_options'));

        this.track_options = JSON.parse(this.$el.parentElement.getAttribute('track_options'));
        this.profile_options = JSON.parse(this.$el.parentElement.getAttribute('profile_options'));
        this.csrf = document.querySelector('[name="csrf-token"]').getAttribute('content');

        this.profiles = {};
        for (const track of this.track_options) {
            this.profiles[track.id] = [];
            for (const member of this.form.members) {
                if (member.track_id == track.id) {
                    let index = this.profile_options.findIndex(itm => itm.id == member.profile_id);
                    this.profiles[track.id].push(this.profile_options[index]);
                }
            }
        }

        this.updateAvailableTracks();

    }

}
</script>


<style lang="scss" scoped>

@import "resources/css/admin-vars";

form.block {

    @media screen and (min-width: 950px) {
        h1 {
            margin-bottom: 2em;
            margin-top: 1em;
        }
        h2 {
            margin-bottom: 2.5em;
            margin-top: 4em;
        }
    }
    @media screen and (max-width: 950px) {
        h1 {
            margin-bottom: 1.5em;
            margin-top: 1em;
        }
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

    &:deep(.field) {
        display: flex;
        width: 100%;
        margin-bottom: 2em;

        .input {
            font-family: sans-serif;
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
            .input-label {
                margin: 0;
                padding-top: 15px;
            }
        }

        .button-wrp {
            display: flex;
        }

        @media screen and (min-width: 1200px) {
            .input-label + div {
                flex-grow: 1;
                display: flex;
                gap: 50px;
            }
            .input-description, .input-error {
                margin: 0;
                padding-top: 15px;
            }
        }

        @media screen and (max-width: 1200px) {
            .input-label + div {
                display: block;
                width: 100%;
            }
            .input-description, .input-error {
                display: inline-block;
                margin-top: 0.6em;
            }
        }

        @media screen and (min-width: 950px) {

            .input-description, .input-error {
                max-width: 500px;
            }

            .field-inner {
                width: 500px;
                flex-shrink: 0;
            }

            .input-label {
                width: 250px;
                flex-shrink: 0;
            }
        }


        @media screen and (max-width: 950px) {
            flex-direction: column;
            gap: 0.8em;
            .input-label {
                padding: 0;
                width: 100%;
                margin-bottom: 1em;
                display: inline-block;
            }
        }


        &.field-edu_level {
            @media screen and (max-width: 950px) {
                .input-label {
                    display: none;
                }
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

        &.field-jurisms {
            @media screen and (min-width: 950px) {
                margin-top: 3em;
            }
        }


    }

    .button-wrp {
        display: flex;

        p {
            margin: 0
        }

        @media screen and (min-width: 950px) {
            display: flex;
            align-items: center;
            gap: 2em;
            margin-top: 5em;
            margin-bottom: 1em;
        }
        @media screen and (max-width: 950px) {
            margin-top: 4em;
            flex-direction: column;
            gap: 2em;
        }
    }

    @media screen and (max-width: 600px) {
        margin-left: -1rem;
        margin-right: -1rem;
        border-radius: 0;
        padding-left: 1rem;
        padding-right: 1rem;
    }

}
</style>

<i18n>
{
    "ru": {

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
        "Зарегистрироваться": "Зарегистрироваться",
        "Поле содержит некорректный номер телефона": "Поле содержит некорректный номер телефона",
        "Уровень образования": "Уровень образования",

        "SUBTITLE_1": "Пожалуйста, введите достоверные данные, иначе результаты Олимпиады могут быть не зачтены",
        "EMAIL_DESCRIPTION": "На указанный Email придёт запрос на подтверждение регистрации. Адрес электронной почты будет использоваться в качестве логина при входе.",
        "SUBTITLE_2": "Выберите уровень (уровни) полученного или получаемого на данный момент образования",
        "SUBTITLE_3": "Выберите треки и профили, по которым хотите принять участие в олимпиаде",
        "CHECK_2": "Даю согласие на обработку персональных данных в соответствии с законодательством Российской Федерации",
        "CHECK_3": "Даю согласие на получение информационных рассылок",
        "HINT_1": "Заполните все поля регистрационной формы",
        "HINT_2": "Укажите уровни образования",
        "HINT_3": "Выберите треки и профили для участия в олимпиаде",
        "HINT_4": "Подтвердите согласие с правилами олимпиады, согласие на обработку персональных данных и на получение информационных рассылок"
    },
    "en": {
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
        "Зарегистрироваться": "Register",
        "Поле содержит некорректный номер телефона": "The field contains an invalid phone number",
        "Уровень образования": "The level of education",

        "SUBTITLE_1": "Please enter reliable data, otherwise the results of the Olympiad may not be counted",
        "EMAIL_DESCRIPTION": "A request for confirmation of registration will be sent to the specified Email address. The email address will be used as the login when logging in.",
        "SUBTITLE_2": "Select the level(s) of education you have received or are currently receiving",
        "SUBTITLE_3": "Select the tracks and profiles that you want to participate in the Olympiad on",
        "CHECK_2": "I give my consent to the processing of personal data in accordance with the legislation of the Russian Federation",
        "CHECK_3": "I give my consent to receive newsletters",
        "HINT_1": "Fill in all fields of the registration form",
        "HINT_2": "Specify the levels of education",
        "HINT_3": "Select tracks and profiles to participate in the Olympiad",
        "HINT_4": "Confirm your agreement with the rules of the Olympiad, consent to the processing of personal data and receipt of newsletters"
    }
}


</i18n>
