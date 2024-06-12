<template>
    <GuestLayout wrapper-class="content-page">
        <img src="/images/index-decor.svg" alt="" class="decorator">

        <div class="block">
            <h1 class="main-page-title" v-if="content.title">{{content.title}}</h1>
            <div class="content-wrapper">
                <div class="content" v-html="content.content"></div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";

defineProps(['content'])

</script>

<style lang="scss">


</style>
