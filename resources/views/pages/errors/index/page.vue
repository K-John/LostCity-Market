<script setup lang="ts">
import { computed } from "vue";
import {
    WrenchIcon,
    ExclamationTriangleIcon,
    MagnifyingGlassIcon,
    LockClosedIcon,
    ArrowLongLeftIcon,
} from "@heroicons/vue/24/outline";

interface Props {
    status: number;
}

const props = defineProps<Props>();
const status = computed(() => props.status ?? 404);

const title = computed(() => {
    return {
        503: "503: Service Unavailable",
        500: "500: Server Error",
        404: "404: Page Not Found",
        403: "403: Forbidden",
    }[status.value];
});

const description = computed(() => {
    return {
        503: "The servers are experiencing lag... please wait a few ticks.",
        500: "Oh dear, you are dead! Our servers need to respawn...",
        404: "You search the area but find nothing of interest.",
        403: "Sorry, adventurer. You don't have the required level to access this page.",
    }[props.status];
});

const iconComponent = computed(() => {
    return (
        {
            503: WrenchIcon,
            500: ExclamationTriangleIcon,
            404: MagnifyingGlassIcon,
            403: LockClosedIcon,
        }[status.value] || ExclamationTriangleIcon
    );
});

const back = () => {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        router.get(route("home"));
    }
};
</script>

<template>
    <LayoutMain>
        <Head :title="title" />

        <div class="my-8 flex flex-col items-center justify-center space-y-6">
            <component :is="iconComponent" class="size-16 text-stone-500" />

            <div class="flex flex-col items-center justify-center space-y-2">
                <h1 class="text-3xl font-bold text-stone-200">{{ title }}</h1>

                <p class="text-center text-lg text-stone-300">
                    {{ description }}
                </p>
            </div>

            <hr class="w-72 border-stone-600" />

            <button
                type="button"
                class="flex w-fit items-center gap-2 rounded-sm bg-green-800 px-3 py-2 text-white hover:bg-green-700"
                @click="back()"
            >
                <ArrowLongLeftIcon class="size-5" />
                Go Back
            </button>
        </div>
    </LayoutMain>
</template>
