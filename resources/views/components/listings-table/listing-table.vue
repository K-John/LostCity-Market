<script setup lang="ts">
interface Props {
    tableClass?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="flex flex-col border-2 border-[#382418] bg-black p-1">
        <template v-if="$slots.header">
            <div class="mb-3 px-2 pt-2">
                <slot name="header" />
            </div>
        </template>

        <table class="w-full" :class="[tableClass]">
            <thead>
                <slot name="table-header" />
            </thead>

            <tbody>
                <slot />
            </tbody>
        </table>

        <template v-if="$slots.footer">
            <div class="px-3">
                <slot name="footer" />
            </div>
        </template>
    </div>
</template>
