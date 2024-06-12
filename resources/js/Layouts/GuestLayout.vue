<script>
import {Link} from '@inertiajs/vue3';
import {Head} from "@inertiajs/vue3";
export default {
    components: {Link, Head},
    props: ['wrapperClass', 'title'],
    mounted() {
        if(this.wrapperClass == 'index-page' || this.wrapperClass == 'content-page'){
            document.body.classList.add('green-decorator');
        } else {
            document.body.classList.remove('green-decorator');
        };

        var closer = this.$refs['sidebar-closer'];
        var opener = this.$refs['sidebar-opener'];
        var sidebar = this.$refs['sidebar'];

        opener.addEventListener('click', function(){
            sidebar.style.display = 'flex';
        });

        closer.addEventListener('click', function(){
            sidebar.style.display = 'none';
        });
    },
    methods: {
        pressEsc (evt) {
            console.log('pressEsc');
            if (evt.key === "Escape") {
                this.closePopupMenu();
            }
        },
        openPopupMenu(){
            this.$refs.headerPopupMenu.style.display = null;
            let $v = this;
            setTimeout(function(){
                $v.$refs.headerPopupMenu.classList.add('opened');
            }, 0);
            document.addEventListener('keydown', this.pressEsc);
        },
        closePopupMenu(){
            if(this.$refs.headerPopupMenu.style.display == 'none'){
                return;
            }
            document.removeEventListener('keydown', this.pressEsc);
            this.$refs.headerPopupMenu.classList.remove('opened');
            let $v = this;
            setTimeout(function(){
                $v.$refs.headerPopupMenu.style.display = 'none';
            }, 300);
        }

    }
}
</script>

<template>
    <div class="lay-wrapper">
        <Head :title="title" />
        <div class="header-wrapper">
            <div class="sidebar" ref="sidebar">
                <div class="sidebar-header">
                    <a href="/"><img src="/images/logo-a.png" class="header-logo" alt=""></a>
                    <a href="javascript:;" class="sidebar-closer" ref="sidebar-closer"></a>
                </div>


                <ul class="sidebar-menu">
                    <li><Link :href="route('login')" class="auth-link" title="Войти">Войти</Link></li>
                    <li><Link :href="route('register')" class="auth-link" title="Зарегистрироваться">Зарегистрироваться</Link></li>
                </ul>

            </div>
            <header>
                <div class="header-side-placeholder">
                    <Link href="/"><img src="/images/logo.png" class="header-logo" alt="" /></Link>
                </div>

                <div class="header-side-placeholder">
                    <template v-if="$page.props.auth.user">
                        <a @click.stop="openPopupMenu" href="javascript:;" class="header-user">
                            <span>{{ $page.props.auth.user.display_name }}</span>
                        </a>
                        <ul class="header-popup-menu" ref="headerPopupMenu" v-click-outside="closePopupMenu" style="display: none">
                            <li class="admin"><a href="/admin" target="_blank">Личный кабинет</a></li>
                            <li><Link :href="route('logout')" method="get">Выйти</Link></li>
                        </ul>
                    </template>
                    <template v-else>
                        <ul class="header-menu">
                            <li><Link :href="route('login')" class="auth-link" title="Войти">Войти</Link></li>
                            <li><Link :href="route('register')" class="auth-link" title="Зарегистрироваться">Зарегистрироваться</Link></li>
                        </ul>
                    </template>
                    <a href="javascript:;" class="sidebar-opener" ref="sidebar-opener"></a>
                </div>
            </header>
        </div>
        <main>
            <div :class="wrapperClass">
                <slot />
            </div>
        </main>
        <div class="lay-footer-placeholder"></div>
    </div>

    <footer>

    </footer>
</template>

<style src="@/../css/guest-layout.scss" lang="scss" />
