<template>

    <BareLayout wrapper-class="solve-page">
        <div class="solve-page-title">
            <img src="/images/logo-s.png" alt="">
            <div >
                <p>Идет тестирование, осталось:</p>
                <countdown :time="time"/>
            </div>
            <!-- <h1>Предпросмотр задания №{{ question.id }}</h1> -->

            <div class="to-right">

                <button class="btn btn-default">Завершить</button>
            </div>
        </div>
        <div class="solve-page-main">
            <h2>Задание 1 из 1</h2>

        </div>
    </BareLayout>
</template>
<script>
import BareLayout from "@/Layouts/BareLayout.vue";
import Countdown from "@/Components/Countdown.vue";
import QuizSolveOne from "@/Quiz/one/Solve.vue";
import {router, useForm} from "@inertiajs/vue3";
import TableBottom from "@/Components/TableBottom.vue";

export default {
    components: {
        TableBottom,
        QuizSolveOne,
        BareLayout,
        Countdown
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
    }

}
</script>

<style lang="scss" scoped>
@import "/resources/css/admin-vars";
:deep(main.solve-page) {
    min-height: 100vh;
}


.solve-page-title {
    img {
        height: 50px;
        margin-right: 1em;
    }

    p {
        margin-bottom: 0;
    }

    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 50px;
    padding: 15px;
    background: white;
    box-shadow: 2px -3px 10px 5px #DBDBDB;
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
    }
}


.solve-page-main {
    max-width: 1200px;
    margin: 0 auto;
    h2 {
        margin-bottom: 2em;
    }

    padding: 15px;
    padding-top: 100px;
    padding-bottom: 100px;

    .question-text {
        margin-top: 0.5em;
        margin-bottom: 1.5em;
        padding-bottom: 1em;
        border-bottom: 1px solid $shadow-color;
    }

}

</style>

