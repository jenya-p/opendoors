<template>
    <li :class="{
                active: item.active,
               'active-branch': item.activeBranch,
                hover: opened == item.key
            }">
        <Link :href="item.href" v-if="item.href" @click="$forceUpdate">
            <i :class="item.i"/>
            <span>{{ item.label }}</span>
        </Link>
        <template v-else-if="item.items">
            <a @click="toggle(item)">
                <i :class="item.i"/>
                <span>{{ item.label }} <b class="caret"></b></span>
            </a>
            <transition name="slide-fade" v-if="item.items">
                <ul v-show="opened == item.key">
                    <SidebarItem v-for="(subItem) of item.items" :item="subItem" />
                </ul>
            </transition>
        </template>
        <span v-else>
            <i :class="item.i"/>
            <span>{{item.label}}</span>
        </span>
    </li>
</template>

<script>
import {Link} from "@inertiajs/vue3";

export default {
    components: {Link},
    props: {
        item: Object, opened: String
    },
    emits: ['update:opened'],
    data() {

    },

    methods: {
        toggle(item) {
            if(this.opened == item.key) {
                this.$emit('update:opened', null);
            } else  {
                this.$emit('update:opened', item.key);
            }
        },


    },
    computed: {

    },
    created() {

    },
    mounted() {

    }
}
</script>
