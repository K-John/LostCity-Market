<script setup lang="ts">
import VueMarkdown from 'vue-markdown-render';

defineProps<Data.Banner.BannerData>();
</script>

<template>
    <Alert :id="`banner-${id}`" :type="type">
        <VueMarkdown
            :source="message"
            class="prose prose-sm prose-stone prose-invert prose-headings:mb-2 prose-headings:mt-0 prose-headings:font-semibold"
        ></VueMarkdown>
    </Alert>
</template>
