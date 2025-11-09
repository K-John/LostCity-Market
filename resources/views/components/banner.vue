<script setup lang="ts">
import VueMarkdown from "vue-markdown-render";

const props = defineProps<{
    banner: Data.Banner.BannerData;
}>();
</script>

<template>
    <Alert :id="`banner-${props.banner.id}`" :type="props.banner.type">
        <VueMarkdown
            :source="props.banner.message"
            class="prose prose-sm prose-stone prose-invert prose-headings:mb-2 prose-headings:mt-0 prose-headings:font-semibold"
        ></VueMarkdown>
    </Alert>
</template>
