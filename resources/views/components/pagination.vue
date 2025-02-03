<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { usePaginator } from "momentum-paginator";

import {
    ArrowLongLeftIcon,
    ArrowLongRightIcon,
} from "@heroicons/vue/24/outline/index.js";

const props = defineProps<{
    data: Paginator<any>;
}>();
const { first, last, previous, next, pages } = usePaginator(props.data);

const visibleRange = 2; // Number of pages to show on either side of current page

const filteredPages = computed(() => {
    const currentPage = Number(pages.find((p) => p.isCurrent)?.label);
    if (!currentPage) return pages;

    const firstPageNumber = Number(pages[0]?.label);
    const lastPageNumber = Number(pages[pages.length - 1]?.label);

    const pageNumbers = pages
        .map((p) => Number(p.label))
        .filter((n) => !isNaN(n));

    let filtered = pageNumbers.filter(
        (num) =>
            num === firstPageNumber || // Always show first page
            num === lastPageNumber || // Always show last page
            num === currentPage || // Always show current page
            (num >= currentPage - visibleRange &&
                num <= currentPage + visibleRange), // Show pages around current
    );

    // Insert ellipses where gaps exist
    let result: (string | number)[] = [];
    let prevNum = null;

    for (let num of filtered) {
        if (prevNum !== null && num - prevNum > 1) {
            result.push("..."); // Add ellipsis if there's a gap
        }
        result.push(num);
        prevNum = num;
    }

    return result
        .map((num) => {
            if (num === "...") return { label: "...", isPage: false }; // Format ellipses
            const page = pages.find((p) => Number(p.label) === num);
            return page ? { ...page, isPage: true } : null;
        })
        .filter(Boolean); // Remove nulls
});
</script>

<template>
    <nav
        v-if="props.data.data.length"
        class="-mx-4 flex items-center justify-between px-4 sm:mx-0 sm:px-0"
    >
        <div class="group">
            <div
                v-if="previous.isActive"
                class="-mt-px h-px w-0 transition-[width] group-hover:w-full"
            ></div>

            <Component
                :is="previous.isActive ? Link : 'span'"
                :href="previous.url!"
                class="inline-flex items-center gap-3 py-3 text-sm font-bold transition"
                :class="[
                    previous.isActive
                        ? 'text-white hover:text-stone-300'
                        : 'cursor-default text-stone-700',
                ]"
            >
                <ArrowLongLeftIcon class="size-5" />

                <span>Prev</span>
            </Component>
        </div>

        <div class="hidden md:flex">
            <div v-for="(page, index) in filteredPages" :key="index">
                <!-- Page numbers -->
                <div v-if="page.isPage" class="group">
                    <div
                        class="-mt-px h-px transition-[width]"
                        :class="[
                            page.isCurrent
                                ? 'w-full'
                                : 'w-0 group-hover:w-full',
                        ]"
                    ></div>

                    <Link
                        :href="page.url!"
                        class="block px-4 py-3 text-sm font-bold transition"
                        :class="[
                            page.isCurrent ? 'text-stone-500' : 'text-white',
                        ]"
                    >
                        {{ page.label }}
                    </Link>
                </div>

                <!-- Ellipsis when skipping pages -->
                <div v-else class="px-3.5 py-3 text-sm font-bold">...</div>
            </div>
        </div>

        <div class="group">
            <div
                v-if="next.isActive"
                class="-mt-px h-px w-0 transition-[width] group-hover:w-full"
            ></div>

            <Component
                :is="next.isActive ? Link : 'span'"
                :href="next.url!"
                class="inline-flex items-center gap-3 py-3 text-sm font-bold transition"
                :class="[
                    next.isActive
                        ? 'text-white hover:text-stone-400'
                        : 'cursor-default text-stone-700',
                ]"
            >
                <span>Next</span>

                <ArrowLongRightIcon class="size-5" />
            </Component>
        </div>
    </nav>
</template>
