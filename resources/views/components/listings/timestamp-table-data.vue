<script setup lang="ts">
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";

defineProps<{
    timestamp: string;
}>();
</script>

<template>
    <td class="px-1">
        <Tooltip>
            <p>{{ fromNow(timestamp) }}</p>

            <template #popper>
                {{ formatTime(timestamp) }}
            </template>
        </Tooltip>
    </td>
</template>
