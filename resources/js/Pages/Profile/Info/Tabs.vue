<script setup>
import {Link} from "@inertiajs/vue3";
</script>
<template>
    <ul class="tabs">
        <li :class="{'active': route().current() == 'profile.edit'}"><Link :href="route('profile.edit')" preserve-scroll>Данные</Link></li>
        <li :class="{'active': route().current() == 'password.edit'}"><Link :href="route('password.edit')" preserve-scroll>Безопасность</Link></li>
    </ul>
</template>

<style scoped lang="scss">
    ul {
        margin-top: 3px;
        margin-bottom: 10px;
    }
</style>
