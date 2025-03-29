<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";

const props = withDefaults(
    defineProps<{
        timestamp: string;
        useColor?: boolean;
    }>(),
    {
        useColor: true,
    },
);

dayjs.extend(relativeTime);

const timeAgo = ref(fromNow(props.timestamp));
let interval: ReturnType<typeof setInterval> | null = null;

// Compute how old the timestamp is
const timeClass = computed(() => {
    if (!props.useColor) return "";

    const createdAt = dayjs(props.timestamp);
    const diffInHours = dayjs().diff(createdAt, "hour");

    if (diffInHours < 1) return "text-green-500";
    if (diffInHours < 6) return "text-yellow-500";
    return "text-stone-300";
});

onMounted(() => {
    interval = setInterval(() => {
        timeAgo.value = fromNow(props.timestamp);
    }, 5_000);
});

onUnmounted(() => {
    if (interval) clearInterval(interval);
});
</script>

<template>
    <td class="sm:px-1">
        <Tooltip>
            <p class="text-sm sm:text-[medium]" :class="timeClass">
                {{ timeAgo }}
            </p>

            <template #popper>
                {{ formatTime(timestamp) }}
            </template>
        </Tooltip>
    </td>
</template>
