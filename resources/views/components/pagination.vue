<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import { usePaginator } from "momentum-paginator";

import {
    ArrowLongLeftIcon,
    ArrowLongRightIcon,
} from "@heroicons/vue/24/outline/index.js";

const props = defineProps<{
    data: Paginator<any>;
}>();
const { first, last, previous, next, pages } = usePaginator(props.data);

// Local types to narrow the pages union
type RawPage = {
    url?: string | null;
    label: string;
    isActive?: boolean;
    isPrevious?: boolean;
    isNext?: boolean;
    isCurrent?: boolean;
    isSeparator?: boolean;
};

type EllipsisItem = { label: '...'; isPage: false };
type PageItem = (RawPage & { isPage: true }) | EllipsisItem;

const visibleRange = 2; // Number of pages to show on either side of current page

// Helper: merge current query params (filters/sort) into the page url
function buildUrl(pageUrl?: string | null): string {
    if (!pageUrl) return "#";

    // Create absolute URL from possibly-relative pageUrl
    const urlObj = new URL(pageUrl, window.location.origin);
    const pageParams = new URLSearchParams(urlObj.search);
    const currentParams = new URLSearchParams(window.location.search);

    // Copy current query params (except `page`) into page params so filters/sorts persist
    for (const [key, value] of currentParams.entries()) {
        if (key === "page") continue;
        pageParams.set(key, value);
    }

    const qs = pageParams.toString();
    return urlObj.pathname + (qs ? `?${qs}` : "");
}

// Type guard used after mapping to filter out nulls
function isPageItem(val: PageItem | null): val is PageItem {
    return val !== null;
}

const filteredPages = computed<PageItem[]>(() => {
    const currentPageLabel = pages.find((p) => p.isCurrent)?.label;
    const currentPage = Number(currentPageLabel);
    if (Number.isNaN(currentPage)) {
        // No current page found - return all pages (mapped to isPage)
        return pages.map((p) => ({ ...p, isPage: true })) as PageItem[];
    }

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
    let prevNum: number | null = null;

    for (let num of filtered) {
        if (prevNum !== null && num - prevNum > 1) {
            result.push("..."); // Add ellipsis if there's a gap
        }
        result.push(num);
        prevNum = Number(num);
    }

    return result
        .map((num) => {
            if (num === "...") return { label: "...", isPage: false } as EllipsisItem;
            const page = pages.find((p) => Number(p.label) === Number(num));
            return page ? ({ ...(page as RawPage), isPage: true } as PageItem) : null;
        })
        .filter(isPageItem); // Narrow type to PageItem[]
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
                :href="buildUrl(previous.url)"
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
                        :href="buildUrl(page.url)"
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
                :href="buildUrl(next.url)"
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
