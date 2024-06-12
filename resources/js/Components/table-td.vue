<template>
    <td class="ttd">
        <template v-if="label">
            <span class="ttd-label">{{ label }}</span>
            <span class="ttd-value"><slot></slot></span>
        </template>
        <slot v-else></slot>
    </td>
</template>

<script>
export default {
    name : "ttd",
    props : [
        'label'
    ]
}
</script>
