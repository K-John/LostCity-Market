<script setup lang="ts">
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import { onBeforeUnmount, onMounted, ref, nextTick } from "vue";

const buttonRef = ref<HTMLElement | null>(null);
const menuStyles = ref<Record<string, string>>({});
</script>

<template>
    <Popover>
        <PopoverButton ref="buttonRef">
            <slot name="button" />
        </PopoverButton>

        <Transition
            enter-active-class="transition ease-out duration-150"
            enter-from-class="opacity-0 translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-1"
        >
            <PopoverPanel class="absolute z-50 max-w-sm rounded-md sm:max-w-md">
                <slot />
            </PopoverPanel>
        </Transition>
    </Popover>
</template>
