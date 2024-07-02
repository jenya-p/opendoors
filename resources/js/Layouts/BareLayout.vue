<script>
import {Head} from '@inertiajs/vue3';

export default {
    components: {Head},
    props: ["wrapperClass", "title"],
}
</script>

<template>
    <Head :title="title"/>
    <div class="lay-wrapper">
        <main :class="wrapperClass">
            <slot/>
        </main>
    </div>
</template>

<style src="@/../css/admin-layout.scss" lang="scss"/>
