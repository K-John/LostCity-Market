<script setup lang="ts">
defineProps<{
    username: string;
}>();
</script>

<template>
    <td class="px-1 text-stone-400">
        {{
            username
                .split(" ")
                .map(
                    (word) =>
                        word.charAt(0).toUpperCase() +
                        word.slice(1).toLowerCase(),
                )
                .join(" ")
        }}
    </td>
</template>
