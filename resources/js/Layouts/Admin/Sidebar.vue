<template>
    <aside :class="minified ? 'min': 'max'">
        <a class="minify-btn" @click="toggleMinified"></a>
        <a href="/" class="logo" target="notter-home"></a>

        <div class="sidebar-menu">
            <ul class="nav">
                <sidebar-item v-for="(item, name) of $page.props.sidebar"
                    :item="item" v-model:opened="curSection"
                />

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
import SidebarItem from "@/Layouts/Admin/SidebarItem.vue";
import _isArray from "lodash/isArray";

export default {
    components: {SidebarItem, Link},
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
        let $v = this;
        this.curRoute = route().current().split('.')[1];
        let doer = function(items){
            let isActive = false;
            for (const item of items) {
                item.active = false;
                if(item.href && $v.curRoute == item.key){
                    item.active = true;
                    isActive = true;
                } else if(_isArray(item.items)){
                    item.activeBranch = doer(item.items);
                    if(item.activeBranch){
                        $v.curSection = item.key;
                    }
                }
            }
            return isActive;
        }
        doer(this.$page.props.sidebar)
        console.log(this.$page.props.sidebar);
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
