<script setup lang="ts">
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";

defineProps<{
    item: Data.Item.ItemData | null;
}>();
</script>

<template>
    <td class="sm:px-1">
        <Tooltip>
            <Link
                v-if="item"
                :href="
                    route('items.show', {
                        item: item.slug,
                    })
                "
            >
                <img
                    :src="`/img/items/${item.slug}.webp`"
                    :alt="`${item.name} Icon`"
                    class="min-h-[24px] min-w-[24px]"
                    width="32"
                    height="32"
                />
            </Link>

            <template #popper>
                {{ item ? item.name : "Unknown Item" }}
            </template>
        </Tooltip>
    </td>
</template>
