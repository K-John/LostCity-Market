<script setup lang="ts">
import { MenuItem } from "@headlessui/vue";
import { computed } from "vue";

const props = defineProps<{
    icon?: Object;
    textColor?: string;
    href?: string;
}>();

const emit = defineEmits<{
    (e: "click"): void;
}>();

const isLink = computed(() => !!props.href);
</script>

<template>
    <MenuItem v-slot="{ active }">
        <component
            :is="isLink ? 'a' : 'button'"
            :href="href"
            class="group flex w-full items-center rounded-md px-3 py-2 text-sm font-bold transition"
            :class="[textColor || 'text-slate-200', { 'bg-stone-600': active }]"
            v-bind="
                isLink ? { target: '_self', rel: 'noopener noreferrer' } : {}
            "
            @click="isLink ? null : emit('click')"
        >
            <component
                :is="icon"
                v-if="icon"
                class="mr-2 size-5"
                aria-hidden="true"
            />

            <slot />
        </component>
    </MenuItem>
</template>
