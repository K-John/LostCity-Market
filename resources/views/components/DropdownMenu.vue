<script setup lang="ts">
import { ref, nextTick, onMounted, onBeforeUnmount, computed } from "vue";
import { Menu, MenuButton, MenuItems } from "@headlessui/vue";
import { EllipsisVerticalIcon } from "@heroicons/vue/24/outline";

type Variant = "success" | "danger" | "secondary" | "warning" | "default";

const props = defineProps<{
    variant?: Variant;
}>();

const buttonRef = ref<HTMLElement | null>(null);
const menuStyles = ref<Record<string, string>>({});

// Computed button classes based on variant prop
const buttonClasses = computed(() => {
    switch (props.variant) {
        case "success":
            return "inline-flex justify-center rounded-sm bg-green-800 p-1 text-white hover:bg-green-700";
        case "danger":
            return "inline-flex justify-center rounded-sm bg-red-800 p-1 text-white hover:bg-red-700";
        case "secondary":
            return "inline-flex justify-center rounded-sm bg-stone-900 p-1 text-whtie hover:bg-stone-800";
        case "warning":
            return "inline-flex justify-center rounded-sm bg-yellow-600 p-1 text-white hover:bg-yellow-500";
        case "default":
        default:
            return "inline-flex justify-center rounded-sm bg-amber-300 p-1 text-amber-900 hover:bg-amber-400";
    }
});

const updateMenuPosition = () => {
    if (buttonRef.value) {
        const rect = buttonRef.value.getBoundingClientRect();
        // Assuming dropdown width of ~160px (w-40)
        menuStyles.value = {
            top: `${rect.bottom + 4}px`,
            left: `${rect.right - 160}px`,
        };
    }
};

const updatePositionHandler = () => {
    updateMenuPosition();
};

onMounted(() => {
    window.addEventListener("resize", updatePositionHandler);
    window.addEventListener("scroll", updatePositionHandler, true);
});
onBeforeUnmount(() => {
    window.removeEventListener("resize", updatePositionHandler);
    window.removeEventListener("scroll", updatePositionHandler, true);
});
</script>

<template>
    <Menu as="div" class="relative inline-block">
        <div ref="buttonRef">
            <MenuButton
                :class="buttonClasses"
                @click="nextTick(updateMenuPosition)"
            >
                <!-- Named slot for icon. Fallback to default icon if not provided. -->
                <slot name="icon">
                    <EllipsisVerticalIcon class="size-5" aria-hidden="true" />
                </slot>
            </MenuButton>
        </div>

        <teleport to="body">
            <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
            >
                <MenuItems
                    :style="[menuStyles, { position: 'fixed' }]"
                    class="z-10 mt-1 w-40 origin-top-right rounded-md bg-stone-700 p-1"
                >
                    <slot />
                </MenuItems>
            </transition>
        </teleport>
    </Menu>
</template>
