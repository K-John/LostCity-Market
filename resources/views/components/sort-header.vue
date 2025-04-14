<script setup lang="ts">
import { ArrowDownIcon, ArrowUpIcon } from "@heroicons/vue/24/outline";
import { computed } from "vue";

interface Props {
    field: string;
    defaultSort?: string;
}

const props = defineProps<Props>();

const { url } = usePage();
const currentUrl = computed(() => new URL(url, window.location.origin));

const currentQuery = computed(() => {
    return Object.fromEntries(currentUrl.value.searchParams.entries());
});

const activeSort = computed(() => {
    return currentQuery.value.sort || props.defaultSort || "";
});

const sortOrder = computed((): "asc" | "desc" | null => {
    if (activeSort.value === props.field) {
        return "asc";
    }
    if (activeSort.value === "-" + props.field) {
        return "desc";
    }
    return null;
});

const computedHref = computed(() => {
    const newQuery = { ...currentQuery.value };

    delete newQuery.page;

    const currentActiveSort = activeSort.value;

    let newSort: string;
    if (currentActiveSort === props.field) {
        newSort = "-" + props.field;
    } else if (currentActiveSort === "-" + props.field) {
        newSort = props.field;
    } else {
        newSort = props.field;
    }

    newQuery.sort = newSort;

    const searchParams = new URLSearchParams(newQuery).toString();

    return `${currentUrl.value.pathname}?${searchParams}`;
});
</script>

<template>
    <Link :href="computedHref" class="flex items-center gap-1">
        <slot></slot>
        
        <span v-if="sortOrder === 'asc'">
            <ArrowUpIcon class="size-4" />
        </span>

        <span v-else-if="sortOrder === 'desc'">
            <ArrowDownIcon class="size-4" />
        </span>
    </Link>
</template>
