<script setup lang="ts">
import { XMarkIcon } from '@heroicons/vue/24/outline';

type AlertType = "error" | "success" | "warning" | "info" | "default";

interface AlertProps {
    type?: AlertType;
    title?: string;
    id?: string;
}

const props = defineProps<AlertProps>();

const isVisible = ref(true);

onMounted(() => {
    if (props.id) {
        const storedState = localStorage.getItem(`alert-${props.id}`);
        if (storedState === "closed") {
            isVisible.value = false;
        }
    }
});

const closeAlert = () => {
    isVisible.value = false;
    if (props.id) {
        localStorage.setItem(`alert-${props.id}`, "closed");
    }
};

const alertClasses = computed(() => {
    const styles: Record<AlertType, string> = {
        error: "border-red-800 bg-red-950 text-white",
        success: "border-green-800 bg-green-950 text-white",
        warning: "border-amber-800 bg-amber-950 text-white",
        info: "border-sky-800 bg-sky-950 text-white",
        default: "border-stone-800 bg-stone-950 text-white",
    };
    return styles[props.type || "default"];
});
</script>

<template>
    <div
        v-if="isVisible"
        :class="alertClasses"
        class="relative mb-4 flex flex-col gap-2 border-2 p-3"
    >
        <button
            v-if="id"
            class="absolute right-0 top-0 p-1 text-white hover:text-gray-400"
            aria-label="Close alert"
            @click="closeAlert"
        >
            <XMarkIcon class="size-6" />
        </button>

        <h2 v-if="title" class="font-bold">{{ title }}</h2>

        <slot></slot>
    </div>
</template>