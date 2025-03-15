<script setup lang="ts">
import { Menu, MenuButton, MenuItems } from "@headlessui/vue";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";
import { ChatBubbleBottomCenterIcon } from "@heroicons/vue/24/solid/index.js";

defineProps<{
    listing: Data.Listing.ListingData;
}>();
</script>

<template>
    <td :colspan="listing.canManage ? 1 : 2" class="max-w-[110px] sm:px-1">
        <template v-if="listing.notes">
            <Tooltip class="hidden sm:block">
                <p class="truncate">
                    {{ listing.notes }}
                </p>

                <template #popper>
                    {{ listing.notes }}
                </template>
            </Tooltip>

            <Menu as="div" class="relative inline-block sm:hidden">
                <div>
                    <MenuButton
                        class="inline-flex justify-center rounded-sm p-1 text-stone-400 hover:text-stone-600"
                    >
                        <ChatBubbleBottomCenterIcon class="size-5" />
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
                        class="absolute right-0 z-10 mt-1 w-max max-w-[60vw] origin-top-right rounded-md bg-stone-700 p-1"
                    >
                        {{ listing.notes }}
                    </MenuItems>
                </transition>
            </Menu>
        </template>
    </td>
</template>
