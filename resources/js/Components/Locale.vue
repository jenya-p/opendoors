<template>
    <span v-if="locale == 'ru'">RU</span>
    <a v-else @click="set('ru')">RU</a>
    <span v-if="locale == 'en'">RU</span>
    <a v-else @click="set('en')">RU</a>
</template>

<script>


export default {

}
</script>

<style scoped lang="scss">
@import "resources/css/admin-vars";
span{background: $attractive-color}
a{background-color: $base-color}

</style>
