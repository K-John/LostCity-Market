<script setup lang="ts">
import { ref, nextTick, onMounted, onBeforeUnmount } from "vue";
import { Menu, MenuButton, MenuItems } from "@headlessui/vue";
import { EllipsisVerticalIcon } from "@heroicons/vue/24/outline";

const buttonRef = ref<HTMLElement | null>(null);
const menuStyles = ref<Record<string, string>>({});

const updateMenuPosition = () => {
  if (buttonRef.value) {
    const rect = buttonRef.value.getBoundingClientRect();
    // Here, we assume your dropdown width is w-36 (~144px).
    // We calculate top and left using viewport coordinates.
    menuStyles.value = {
      top: `${rect.bottom + 4}px`,
      left: `${rect.right - 144}px`,
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
        class="inline-flex justify-center rounded-sm bg-amber-300 p-1 text-amber-900 hover:bg-amber-400"
        @click="nextTick(updateMenuPosition)"
      >
        <EllipsisVerticalIcon class="size-5" aria-hidden="true" />
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
        <!-- Apply fixed positioning so that the computed viewport coordinates work correctly -->
        <MenuItems
          :style="[menuStyles, { position: 'fixed' }]"
          class="z-10 mt-1 w-36 origin-top-right rounded-md bg-stone-700 p-1"
        >
          <slot />
        </MenuItems>
      </transition>
    </teleport>
  </Menu>
</template>
