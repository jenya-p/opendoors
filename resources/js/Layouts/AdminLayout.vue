<script>
import {Link, router} from '@inertiajs/vue3';
import {Head} from "@inertiajs/vue3";
import Sidebar from "@/Layouts/Admin/Sidebar.vue";
import Locale from "@/Components/Locale.vue";
export default {
    components: {Link, Head, Sidebar, Locale},
    props: ["title", "wrapperClass", 'breadcrumb'],
    data(){
      return {isLk: false};
    },
    mounted() {
        if(route().current().startsWith('lk.')){
            this.isLk = true;
        }
    }
}
</script>

<template>
    <div class="lay-wrapper">
        <Head :title="title" />
        <Sidebar />
        <main>
            <div class="page-title">
                <h1 v-if="!isMobile">{{title}}</h1>
                <Locale class="locale-switcher" v-if="isLk"/>
            </div>

            <div class="breadcrumb-wrapper">
                <ol class="breadcrumb" style="background: transparent"  v-if="breadcrumb && breadcrumb.length > 1 && !isMobile">
                    <template v-for="item of breadcrumb">
                        <li class="breadcrumb-item" v-if="typeof(item) == 'object' && item && item != null && item.link">
                            <a :href="item.link" >{{ item.label}}</a>
                        </li>
                        <li class="breadcrumb-item active" v-else-if="item != null">{{ item }}</li>
                    </template>
                </ol>
                <div class="breadcrumb" v-else-if="breadcrumb && isMobile">
                    <Link :href="breadcrumb[breadcrumb.length - 2].link" v-if="breadcrumb.length > 1" class="fa fa-chevron-left"></Link>
                    <span>{{breadcrumb[breadcrumb.length - 1].label ? breadcrumb[breadcrumb.length - 1].label: breadcrumb[breadcrumb.length - 1]}}</span>
                </div>
            </div>
            <div :class="wrapperClass">
                <slot />
            </div>
        </main>
    </div>


</template>

<style src="@/../css/admin-layout.scss" lang="scss" />
