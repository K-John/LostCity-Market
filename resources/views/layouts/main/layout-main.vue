<script lang="ts"></script>

<template>
    <div class="mx-auto w-[600px]">
        <div class="flex">
            <img src="@/img/assets/edge_a.jpg" />

            <img src="@/img/assets/edge_c.jpg" />

            <img src="@/img/assets/edge_d.jpg" />
        </div>

        <div class="bg-[url('@/img/assets/background2.jpg')] px-10">
            <slot />
        </div>

        <div class="flex items-end">
            <img src="@/img/assets/edge_g2.jpg" />

            <img src="@/img/assets/edge_c.jpg" class="mb-[-2px]" />

            <img src="@/img/assets/edge_h2.jpg" />
        </div>
    </div>
</template>
