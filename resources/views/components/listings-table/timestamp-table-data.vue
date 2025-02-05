<script setup lang="ts">
import { ref, onMounted, onUnmounted } from "vue";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";

const props = defineProps<{
    timestamp: string;
}>();

const timeAgo = ref(fromNow(props.timestamp));
let interval: ReturnType<typeof setInterval> | null = null;

onMounted(() => {
    interval = setInterval(() => {
        timeAgo.value = fromNow(props.timestamp);
    }, 60000); // 60,000ms = 1 minute
});

onUnmounted(() => {
    if (interval) clearInterval(interval);
});
</script>

<template>
    <td class="px-1">
        <Tooltip>
            <p>{{ timeAgo }}</p>

            <template #popper>
                {{ formatTime(timestamp) }}
            </template>
        </Tooltip>
    </td>
</template>