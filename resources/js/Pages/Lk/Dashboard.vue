<template>
    <AdminLayout :breadcrumb="['Личный кабинет']" title="Личный кабинет">
        <h1>{{ $t('WELCOME') }}</h1>
    </AdminLayout>
</template>
<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    components: {AdminLayout}
}

</script>

<i18n src="/resources/js/Pages/Lk/translations.json"/>
