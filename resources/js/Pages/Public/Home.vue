<template>
    <GuestLayout wrapper-class="index-page">
        <img src="/images/index-decor.svg" alt="">
        <div class="index-page--right">
            <h1>Добро пожаловать</h1>

        </div>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import {Link} from "@inertiajs/vue3";

defineProps(['content'])

</script>

<style lang="scss">

.index-page {
    display: flex;
    gap: 50px;
    justify-content: center;

    h1 {
        font-size: 36px;
        font-weight: 600;
        color: white;
        margin-bottom: 30px;
    }

    p {
        color: white;
        max-width: 400px;
        margin-bottom: 45px;
    }

    h1 {
        font-weight: 600;
        color: white;
        margin-bottom: 30px;
    }

    @media screen and (min-width: 1000px) {
        align-items: center;
        min-height: calc(100vh - 250px);
        max-width: 100%;
        h1 {
            font-size: 36px;
        }
        img {
            max-width: 550px;
            max-height: 75vh;
        }
        .index-page--right {
            flex-shrink: 0;
            width: 550px;
        }
    }

    @media screen and (max-width: 1250px) {
        img {
            max-width: 350px;
        }
    }

    @media screen and (max-width: 1000px) {
        margin: 0 auto;
        flex-direction: column;
        max-width: 550px;

        img {
            max-width: 270px;
            margin: 40px auto 0;
        }

    }

    @media screen and (max-width: 500px) {
        padding: 0 10px 50px 10px;
        text-align: center;

        img {
            max-width: 270px;
            margin-top: 40px;
        }

    }
}
</style>
