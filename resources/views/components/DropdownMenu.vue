<script setup lang="ts">
import { Menu, MenuButton, MenuItems } from "@headlessui/vue";
import { EllipsisVerticalIcon } from "@heroicons/vue/24/outline";
</script>

<template>
    <Menu as="div" class="relative inline-block">
        <div>
            <MenuButton
                class="inline-flex justify-center rounded-sm bg-amber-300 p-1 text-amber-900 hover:bg-amber-400"
            >
                <EllipsisVerticalIcon class="size-5" aria-hidden="true" />
            </MenuButton>
        </div>

        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <MenuItems
                class="absolute right-0 z-10 mt-1 w-36 origin-top-right rounded-md bg-stone-700 p-1"
            >
                <slot />
            </MenuItems>
        </transition>
    </Menu>
</template>
