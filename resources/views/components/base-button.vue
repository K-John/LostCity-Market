<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps<{
    href?: string;
    type?: "button" | "submit" | "reset";
    as?: "button" | "link";
    variant?: "success" | "danger" | "secondary" | "warning" | "default" | "custom";
    class?: string;
    forceFocus?: boolean;
}>();

const focusClasses = computed(() => {
  if (props.forceFocus) {
    switch (props.variant) {
        case "success":
            return "ring-2 ring-green-600";
        case "danger":
            return "ring-2 ring-red-600";
        case "warning":
            return "ring-2 ring-yellow-400";
        case "secondary":
            return "ring-2 ring-stone-700";
        case "default":
            return "ring-2 ring-stone-500";
    }
  }

  return '';
});

const isLink = computed(() => props.as === "link" && props.href);

const variantClasses = computed(() => {
    switch (props.variant) {
        case "success":
            return "bg-green-800 text-white hover:bg-green-700 active:bg-green-900 focus:ring-green-600";
        case "danger":
            return "bg-red-800 text-white hover:bg-red-700 active:bg-red-900 focus:ring-red-600";
        case "warning":
            return "bg-yellow-600 text-white hover:bg-yellow-500 active:bg-yellow-700 focus:ring-yellow-400";
        case "secondary":
            return "bg-stone-900 text-white hover:bg-stone-800 active:bg-stone-950 focus:ring-stone-700";
        case "default":
            return "bg-stone-700 text-white hover:bg-stone-600 active:bg-stone-800 focus:ring-stone-500";
        default:
            return "bg-stone-700 text-white hover:bg-stone-600 active:bg-stone-800 focus:ring-stone-500";
    }
});

const baseClasses = "w-fit rounded-sm px-3 py-2 sm:py-1 sm:px-2 transition focus:outline-none focus:ring-2";
</script>

<template>
    <component
        :is="isLink ? Link : 'button'"
        :href="isLink ? href : undefined"
        :type="!isLink ? (type ?? 'button') : undefined"
        :class="[baseClasses, variantClasses, focusClasses, props.class]"
    >
        <slot />
    </component>
</template>
