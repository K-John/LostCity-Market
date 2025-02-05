<script setup lang="ts"></script>

<template>
    <div class="flex flex-col border-2 border-[#382418] bg-black p-1">
        <table>
            <tbody>
                <slot />
            </tbody>
        </table>

        <template v-if="$slots.footer">
            <div class="px-3">
                <slot name="footer" />
            </div>
        </template>
    </div>
</template>