

<template>
    <AdminLayout :breadcrumb="['Личный кабинет']" title="Личный кабинет">
        <h1>{{ $t('h1') }}</h1>
    </AdminLayout>
</template>
<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    components: {AdminLayout}
}

</script>

<i18n>
{
    "ru": {
        "h1": "Добро пожаловать в личный кабинет участника"
    },
    "en": {
        "h1": "Welcome to the participant's personal account"
    }
}
</i18n>
