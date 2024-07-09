<template>
    <aside :class="minified ? 'min': 'max'">
        <a class="minify-btn" @click="toggleMinified"></a>
        <a href="/" class="logo" target="notter-home"></a>

        <div class="sidebar-menu">
            <ul class="nav">
                <li :class="{'active-branch': routeIs('user', 'admin', 'university-user', 'student'), 'hover': curSection=='user'}">
                    <a @click="toggle('user')">
                        <i class="fa fa-users"/>
                        <span>Пользователи
                            <b class="caret"></b>
                        </span>
                    </a>
                    <transition name="slide-fade">
                        <ul v-show="curSection=='user'">
                            <li :class="{active: routeIs('user')}">
                                <Link :href="route('admin.user.index')">
                                    <i class="fa fa-key"/>
                                    <span>Все пользователи</span>
                                </Link>
                            </li>
                            <li :class="{active: routeIs('admin')}">
                                <Link :href="route('admin.admin.index')">
                                    <i class="fa fa-user-tie"/>
                                    <span>Администраторы</span>
                                </Link>
                            </li>
                            <li :class="{active: routeIs('university-user')}">
                                <Link :href="route('admin.university-user.index')">
                                    <i class="fa fa-person-chalkboard"/>
                                    <span>Представители ВУЗ-ов</span>
                                </Link>
                            </li>
                            <li :class="{active: routeIs('student')}">
                                <Link :href="route('admin.student.index')">
                                    <i class="fa fa-users"/><span>Участники</span>
                                </Link>
                            </li>
                            <li>
                                <span>
                                    <i class="fa fa-clock-rotate-left"/>
                                    <span>Журнал</span>
                                </span>
                            </li>
                        </ul>
                    </transition>
                </li>

                <li :class="{'active-branch': routeIs('content','news','profile-file','widget'), 'hover': curSection=='site'}">
                    <a @click="toggle('site')">
                        <i class="fa-solid fa-globe"></i>
                        <span>Сайт<b class="caret"></b></span>
                    </a>
                    <transition name="slide-fade">
                        <ul v-show="curSection=='site'">

                            <li :class="{'active': routeIs('widget')}">
                                <Link :href="route('admin.widget.index')">
                                    <i class="fa fa-icons"/>
                                    <span>Виджеты</span>
                                </Link>
                            </li>

                            <li :class="{'active': routeIs('news')}">
                                <Link :href="route('admin.news.index')">
                                    <i class="fa fa-newspaper"/>
                                    <span>Новости</span>
                                </Link>
                            </li>


                            <li :class="{'active': routeIs('schedule')}">
                                <Link :href="route('admin.schedule.index')">
                                    <i class="fa fa-calendar"/>
                                    <span>График</span>
                                </Link>
                            </li>

                            <li :class="{'active': routeIs('faq')}">
                                <Link :href="route('admin.faq.index')">
                                    <i class="fa fa-question-circle"/>
                                    <span>FAQ</span>
                                </Link>
                            </li>

                            <li>
                                <span>
                                    <i class="fa fa-file-pdf"/>
                                    <span>Файлы</span>
                                </span>
                            </li>

<!--                            <li :class="{'active': routeIs('profile-file')}">-->
<!--                                <Link :href="route('admin.profile-file.index')">-->
<!--                                    <i class="fa fa-file-pdf"/>-->
<!--                                    <span>Файлы</span>         -->
<!--                                </Link>-->
<!--                            </li>-->
                        </ul>
                    </transition>
                </li>

                <li :class="{'active-branch': routeIs('edu-level', 'university', 'track', 'profile', 'stage'), 'hover': curSection=='opendoors'}">
                    <a @click="toggle('opendoors')">
                        <i class="fa fa-door-open"></i>
                        <span>Open Doors<b class="caret"></b></span>
                    </a>
                    <transition name="slide-fade">
                        <ul v-show="curSection=='opendoors'">

                            <li :class="{'active': routeIs('edu-level')}">
                                <Link :href="route('admin.edu-level.index')">
                                    <i class="fa fa-bars"/>
                                    <span>Уровни образования</span>
                                </Link>
                            </li>
                            <li :class="{'active': routeIs('university')}">
                                <Link :href="route('admin.university.index')">
                                    <i class="fa fa-bars"></i>
                                    <span>Университеты</span>
                                </Link>
                            </li>
                            <li :class="{'active': routeIs('track')}">
                                <Link :href="route('admin.track.index')">
                                    <i class="fa fa-bars"></i>
                                    <span>Треки</span>
                                </Link>
                            </li>
                            <li :class="{'active': routeIs('profile')}">
                                <Link :href="route('admin.profile.index')">
                                    <i class="fa fa-bars"></i>
                                    <span>Профили</span>
                                </Link>
                            </li>
                            <li :class="{'active': routeIs('stage')}">
                                <Link :href="route('admin.stage.index')">
                                    <i class="fa fa-bars"></i>
                                    <span>Этапы</span>
                                </Link>
                            </li>
                        </ul>
                    </transition>
                </li>

                <li :class="{'active-branch': routeIs('quiz','quiz-question'), 'hover': curSection=='quiz'}">
                    <a @click="toggle('quiz')">
                        <i class="fa-solid fa-spell-check"></i>
                        <span>Тестирования<b class="caret"></b></span>
                    </a>
                    <transition name="slide-fade">
                        <ul v-show="curSection=='quiz'">
                            <li :class="{'active': routeIs('quiz')}">
                                <Link :href="route('admin.quiz.index')">
                                    <i class="fa fa-list-check"/>
                                    <span>Группы заданий</span>
                                </Link>
                            </li>
                            <li :class="{'active': routeIs('quiz-question')}">
                                <Link :href="route('admin.quiz-question.index')">
                                    <i class="fa fa-list-check"/>
                                    <span>Задания</span>
                                </Link>
                            </li>
                            <li>
                                <span>
                                    <i class="fa fa-user-check"></i>
                                    <span>Попытки</span>
                                </span>
                            </li>
                            <li>
                                <span>
                                    <i class="fa fa-check-double"></i>
                                    <span>Проверка</span>
                                </span>
                            </li>
                        </ul>
                    </transition>
                </li>

                <li :class="{'active': routeIs('portfolio'), 'hover': curSection=='portfolio'}">
                    <span @click="toggle('portfolio')">
                        <i class="fa fa-address-card"></i>
                        <span>Портфолио</span>
                    </span>
                </li>

                <li :class="{'active-branch': routeIs(), 'hover': curSection=='interview'}">
                    <a @click="toggle('interview')">
                        <i class="fa-solid fa-comments"></i>
                        <span>Собеседования<b class="caret"></b></span>
                    </a>
                    <transition name="slide-fade">
                        <ul v-show="curSection=='interview'">
                            <li :class="{'active': routeIs('')}">
                                <span>
                                    <i class="fa fa-clock"/>
                                    <span>Слоты</span>
                                </span>
                            </li>
                            <li :class="{'active': routeIs('')}">
                                <span>
                                    <i class="fa-solid fa-people-arrows"></i>
                                    <span>Собеседования</span>
                                </span>
                            </li>
                            <li :class="{'active': routeIs('')}">
                                <span>
                                    <i class="fa fa-chalkboard-teacher"></i>
                                    <span>Научные руководители</span>
                                </span>
                            </li>
                            <li :class="{'active': routeIs('')}">
                                <span>
                                    <i class="fa fa-users"></i>
                                    <span>Участники</span>
                                </span>
                            </li>
                            <li :class="{'active': routeIs('')}">
                                <span>
                                    <i class="fa-solid fa-people-arrows"></i>
                                    <span>Мои собеседования</span>
                                </span>
                            </li>
                            <li :class="{'active': routeIs('')}">
                                <span>
                                    <i class="fa fa-chalkboard-teacher"></i>
                                    <span>Выбор научного руководителя</span>
                                </span>
                            </li>
                        </ul>
                    </transition>
                </li>

            </ul>
        </div>

        <div class="current-user">
            <span class="username">{{ $page.props.auth.user.display_name }}</span>
            <div class="actions">
                <Link :href="route('admin.user.edit', {user: $page.props.auth.user.id})" class="profile">Профиль</Link>
                <a :href="route('logout')" class="btn-a logout">Выход</a>
            </div>
        </div>

    </aside>

</template>

<script>
import {Link} from "@inertiajs/vue3";

export default {
    components: {Link},
    data() {
        let minified = false;
        if (window.innerWidth < 980) {
            minified = true;
        } else if (localStorage.hasOwnProperty('sidebar-state')) {
            minified = localStorage.getItem('sidebar-state') == 'true';
        }
        return {
            curSection: null,
            minified: minified,
        }
    },
    methods: {
        toggle(section) {
            if (section == this.curSection) {
                this.curSection = null;
            } else {
                this.curSection = section;
            }
        },
        routeIs(...args) {
            for (const arg of args) {
                if (route().current().startsWith('admin.' + arg + '.')) {
                    return true;
                }
            }
            return false;
        },
        toggleMinified() {
            this.minified = !this.minified;
            if (window.innerWidth > 980) {
                localStorage.setItem('sidebar-state', this.minified);
                if(this.minified){
                    this.curSection = null;
                } else {

                }
            }
        }

    },
    computed: {
        backfeeds() {
            return this.$page.props.notifications.backfeed;
        }
    },
    created() {
        this.curRoute = route().current().split('.')[1];
        if (['user', 'admin', 'university-user', 'student', 'history'].indexOf(this.curRoute) !== -1) {
            this.curSection = 'user';
        } else if(this.routeIs('edu-level', 'university', 'track', 'profile', 'stage')){
            this.curSection = 'opendoors';
        } else if(this.routeIs('quiz', 'quiz-question')){
            this.curSection = 'quiz';
        } else if(this.routeIs('news', 'profile-file', 'widget', 'schedule', 'faq')){
            this.curSection = 'site';
        }

    },
    mounted() {
        let $v = this;
        let lis = this.$el.querySelectorAll('.sidebar-menu>ul>li');

        let opened = null;
        for (const li of lis) {
            let doer = function () {
                let tmOut = null;
                let cs = null;
                li.addEventListener('mouseleave', function () {
                    if (window.innerWidth > 980 && $v.minified) {
                        cs = $v.curSection;
                        tmOut = setTimeout(function () {
                            if(cs == $v.curSection){
                                $v.curSection = null;
                            }
                            tmOut = null;
                        }, 500);
                    }
                });
                li.addEventListener('mouseenter', function () {
                    if (window.innerWidth > 980 && $v.minified) {
                        if(tmOut){
                            clearTimeout(tmOut);
                            tmOut = null;
                        }
                    }
                });
            }
            doer();

        }

    }
}
</script>
