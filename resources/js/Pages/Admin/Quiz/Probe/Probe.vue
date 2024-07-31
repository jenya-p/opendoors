<template>

    <BareLayout wrapper-class="solve-page">
        <div class="solve-page-title">
            <img src="/images/logo-s.png" alt="">
            <div >
                <p>{{$t('greeting')}}</p>
                <countdown :time="time"/>
            </div>
            <!-- <h1>Предпросмотр задания №{{ question.id }}</h1> -->

            <div class="to-right">
                <Locale />
                <Link :href="route('admin.quiz-question.edit', {question: question.id})" class="btn btn-default">
                    {{ $t('done') }}</Link>
            </div>
        </div>
        <div class="solve-page-main">
            <h2>{{$t('header')}}</h2>

            <form class="block" @submit="submit">
                <div class="question-image-wrapper">
                    <img v-for="itm of question.images" :src="itm.download_url" alt="" class="">
                </div>

                <div class="ck-content question-text" v-html="question.text"></div>

                <div class="options">
                    <QuizSolveOne
                        v-if="question.type == 'one'"
                        :question="question"
                        v-model:solution="form.solution"
                        v-model:valid="valid"
                        :errors="errors"/>

                    <QuizSolveMany
                        v-else-if="question.type == 'many'"
                        :question="question"
                        v-model:solution="form.solution"
                        v-model:valid="valid"
                        :errors="errors"/>

                    <QuizSolveMulti
                        v-else-if="question.type == 'multi'"
                        :question="question"
                        v-model:solution="form.solution"
                        v-model:valid="valid"
                        :errors="errors"/>

                    <QuizSolveNumber
                        v-else-if="question.type == 'number'"
                        :question="question"
                        v-model:solution="form.solution"
                        v-model:valid="valid"
                        :errors="errors"/>

                    <QuizSolveWords
                        v-else-if="question.type == 'words'"
                        :question="question"
                        v-model:solution="form.solution"
                        v-model:valid="valid"
                        :errors="errors"/>

                    <QuizSolveFree
                        v-else-if="question.type == 'free'"
                        :question="question"
                        v-model:solution="form.solution"
                        v-model:valid="valid"
                        :errors="errors"/>

                    <QuizSolveMatch
                        v-else-if="question.type == 'match'"
                        :question="question"
                        v-model:solution="form.solution"
                        v-model:valid="valid"
                        :errors="errors"/>

                </div>
                <table-bottom align="left">
                    <div>
                        <button class="btn btn-primary" :disabled="!valid">{{ $t('save') }}</button>
                    </div>
                </table-bottom>
            </form>
        </div>
        <!-- <div class="solve-page-footer">

        </div> -->
    </BareLayout>
</template>
<script>
import BareLayout from "@/Layouts/BareLayout.vue";
import Countdown from "@/Components/Countdown.vue";
import QuizSolveOne from "@/Quiz/one/Solve.vue";
import {router, useForm, Link} from "@inertiajs/vue3";
import TableBottom from "@/Components/TableBottom.vue";
import QuizSolveMany from "@/Quiz/many/Solve.vue";
import QuizSolveMulti from "@/Quiz/multi/Solve.vue";
import QuizSolveNumber from "@/Quiz/number/Solve.vue";
import QuizSolveWords from "@/Quiz/words/Solve.vue";
import QuizSolveFree from "@/Quiz/free/Solve.vue";
import QuizSolveMatch from "@/Quiz/match/Solve.vue";
import Locale from "@/Components/Locale.vue";



export default {
    components: {
        Locale,
        QuizSolveMatch,
        QuizSolveFree,
        QuizSolveWords,
        QuizSolveNumber,
        QuizSolveMulti,
        QuizSolveMany,
        TableBottom,
        QuizSolveOne,
        BareLayout,
        Countdown,
        Link
    },
    props: {
        time: {},
        errors: null,
        question: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            valid: false,
            form: useForm({
                question_id: this.question.id,
                solution: null
            })

        }
    },
    watch: {},

    methods: {
        submit() {
            let $v = this;
            this.form.post(route('admin.quiz-probe.check'));
        }
    },
    mounted() {
        if (window.MathJax){
            MathJax.Hub.Typeset();
        }
    },
    updated() {
        if (window.MathJax){
            MathJax.Hub.Typeset();
        }
    }
}
</script>

<style lang="scss" scoped>
@import "/resources/css/admin-vars";
:deep(main.solve-page) {
    min-height: 100vh;
}


.solve-page-title {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 50px;
    padding: 15px;
    background: white;
    box-shadow: 2px -3px 10px 5px #DBDBDB;

    img {
        height: 50px;
        margin-right: 1em;
    }

    p {
        margin-bottom: 0;
    }


}

.solve-page-footer {
    background: white;
    position: fixed;
    bottom: 0;
    padding: 15px;
    right: 0;
    left: 0;
    height: 50px;
    box-shadow: 2px 3px 10px 5px #DBDBDB;


}

.solve-page-title,
.solve-page-footer {
    display: flex;
    align-items: center;

    .to-right {
        margin-left: auto;
        margin-right: 0;
        text-align: right;
        display: flex;
        align-items: center;
        gap: 40px;
    }
}


.solve-page-main {
    max-width: 1200px;
    margin: 0 auto;
    padding: 15px;
    padding-top: 100px;
    padding-bottom: 100px;

    h2 {
        margin-bottom: 2em;
    }

    .question-image-wrapper{
        img{
            max-width: 100%;
        }
    }
    .question-text {
        margin-top: 0.5em;
        margin-bottom: 1.5em;
        padding-bottom: 1em;
        border-bottom: 1px solid $shadow-color;
    }

}

</style>

<i18n>
{
    "ru": {
        "greeting": "Идет тестирование, осталось:",
        "header": "Задание 1 из 1",
        "done": "Завершить",
        "save": "Сохранить ответ"
    },
    "en": {
        "greeting": "Testing is underway:",
        "header": "Task 1 of 1",
        "done":  "Complete",
        "save":  "Save answer"
    }
}
</i18n>
