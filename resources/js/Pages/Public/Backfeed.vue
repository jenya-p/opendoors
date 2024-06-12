<template>
    <GuestLayout wrapper-class="content-page">
        <img src="/images/index-decor.svg" alt="" class="decorator">

        <div class="block">
            <h1 class="main-page-title">Обратная связь</h1>
            <p class="info-1"  v-if="status!='ok'">Уважаемые посетители! Если вы хотите сообщить об ошибке в вопросе, пожалуйста,
                указывайте текст вопроса (желательно полностью) и ответ который нужно исправить. Спасибо!</p>
            <div class="block-bordered success-message" v-if="status=='ok'">
                <p class="info-3">Ваше сообщение отправлено. Скоро администраторы свяжутся с Вами! </p>
                <div class="buttons">
                    <Link href="/" class="btn btn-default btn-sm">ОК</Link>
                </div>
            </div>
            <form method="post" @submit.prevent="submit" class="block-bordered" v-else>
                <p class="info-2">Обязательно нужно написать сообщение и ввести цифры с картинки, остальные поля
                    заполняются по Вашему желанию. Спасибо, что Вы помогаете улучшать и развивать сайт!</p>

                <div class="fields-row">
                    <field :errors="form.errors" for="name" label="Ваше имя">
                        <input type="text" v-model="form.name" class="input" placeholder="Имя"/>
                    </field>

                    <field :errors="form.errors" for="email">
                        <template #label>
                            Ваш e-mail <span class="field-label-description">(на него будет выслан ответ):</span>
                        </template>
                        <template #default>
                            <input type="text" v-model="form.email" class="input" placeholder="e-mail"/>
                        </template>
                    </field>
                </div>
                <field :errors="form.errors" for="subject">
                    <template #label>
                        Тема сообщения <span class="field-label-description">(обязательное поле):</span>
                    </template>
                    <template #default>
                        <textarea-autosize v-model="form.subject" class="input input-subject" rows="1"
                                           placeholder="Напишите тему"/>
                    </template>
                </field>

                <field :errors="form.errors" for="body" label="Сообщение">
                    <textarea-autosize v-model="form.body" class="input input-body" placeholder="Напишите сообщение"  rows="4"/>
                </field>

                <field :errors="form.errors" for="files,files.*" label="Присоединить файл">
                    <input type="file" multiple @change="form.files = $event.target.files"/>
                </field>

                <div class="buttons">
                    <button class="btn btn-default btn-sm">
                        Отправить
                    </button>
                </div>

            </form>
        </div>
    </GuestLayout>
</template>

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Field from "@/Components/Field.vue";
import {useForm, Link} from "@inertiajs/vue3";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";

export default {
    components: {TextareaAutosize, Field, GuestLayout, Link},
    props: ['status'],
    data() {
        return {
            form: useForm({
                name: this.$page.props.auth.user ? this.$page.props.auth.user.display_name: '',
                email: this.$page.props.auth.user ? this.$page.props.auth.user.email: '',
                subject: '',
                body: '',
                files: []
            })
        }
    },
    methods: {
        submit(){
            this.form.post(route('backfeed.store'),{
                forceFormData: true,
            });
        }
    }
}


</script>

<style lang="scss" scoped>
.block{
    padding: 28px 30px;
}
.main-page-title {
    border-bottom: 1px solid #E5E7EB;
    padding-bottom: 9px;
    margin: 0 0 10px 0;
}

.info-1 {
    padding: 20px;
    border-radius: 8px;
    background: #F9F9F9;
    font-size: 12px;
    font-weight: 400;
    margin-bottom: 9px;
}

.info-2 {
    font-size: 14px;
    font-weight: 600;
    margin-top: -5px;
    padding-bottom: 7px;
    margin-bottom: 6px;
    border-bottom: 1px solid #E5E7EB;
}

.field-label-description {
    font-weight: normal;
}

.input {
    width: 100%;
    height: 50px;
}
input[type=file]{
    width: 100%;
    margin-bottom: 5px;
}

.fields-row {
    display: flex;
    gap: 10px;
    width: 100%
}

.input-subject,.input-body{
    padding-top: 16px;
    padding-bottom: 11px;
    resize: none;
}


.buttons {
    margin-top: 25px;
    margin-bottom: 5px;
    .btn{
        width: 240px;
    }
}

.success-message{
    min-height: 300px; display: flex; flex-direction: column; align-items: center;
    justify-content: center;
    .info-3{
        font-size: 14px;
        font-weight: 600;
    }
}

:deep {
    .field {
        width: 100%
    }

    .input-label {
        font-size: 12px;
        margin-bottom: 8px;
    }

}

</style>
