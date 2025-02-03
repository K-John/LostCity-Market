<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";
import {
    PencilSquareIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline/index.js";

const props = defineProps<Pages.ItemsShowPage>();

const listingTypes = computed((): Enums.ListingType[] => ["buy", "sell"]);

const form = useForm({
    ...props.listingForm,
});

const submit = () => {
    form.post(route("listings.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const destroy = (id: number) => {
    router.delete(route("listings.destroy", { listing: id }), {
        preserveScroll: true,
    });
};
</script>

<template>
    <LayoutMain>
        <Head :title="item.name" />

        <ItemSearch />

        <div class="flex flex-col gap-6">
            <div class="flex gap-4">
                <div class="h-fit border-2 border-[#382418] bg-black p-1">
                    <img
                        :src="`/img/items/${item.slug}.png`"
                        :alt="`${item.name} Icon`"
                        width="32"
                        height="32"
                    />
                </div>

                <div class="flex flex-col gap-1">
                    <h1 class="text-2xl font-bold">
                        {{ item.name }}
                    </h1>

                    <div class="flex flex-row gap-4">
                        <h2 class="font-bold">Prices:</h2>

                        <div>
                            <u>Gen. Store:</u>

                            <p>
                                ~{{
                                    Math.floor(
                                        item.cost * 0.4,
                                    ).toLocaleString()
                                }}GP
                            </p>
                        </div>

                        <div>
                            <u>High Alch:</u>

                            <p>
                                {{
                                    Math.floor(
                                        item.cost * 0.6,
                                    ).toLocaleString()
                                }}GP
                            </p>
                        </div>

                        <div>
                            <u>Low Alch:</u>

                            <p>
                                {{
                                    Math.floor(
                                        item.cost * 0.4,
                                    ).toLocaleString()
                                }}GP
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="relative z-10 mb-[-2px] flex flex-row">
                    <Link
                        :href="
                            route('items.show', {
                                item: item,
                                type: listingTypes[0],
                            })
                        "
                        preserve-scroll
                        class="px-4 py-3 font-bold"
                        :class="{
                            'border-2 border-b-0 border-[#382418] bg-black':
                                props.listingType === listingTypes[0],
                        }"
                    >
                        Buy Listings
                    </Link>

                    <Link
                        :href="
                            route('items.show', {
                                item: item,
                                type: listingTypes[1],
                            })
                        "
                        preserve-scroll
                        class="px-4 py-3 font-bold"
                        :class="{
                            'border-2 border-b-0 border-[#382418] bg-black':
                                props.listingType === listingTypes[1],
                        }"
                    >
                        Sell Listings
                    </Link>
                </div>

                <div class="flex flex-col border-2 border-[#382418] bg-black">
                    <table class="border-separate border-spacing-2">
                        <tbody>
                            <tr v-if="!listings.data.length">
                                <td class="text-center" colspan="4">
                                    No listings found.
                                </td>
                            </tr>

                            <tr
                                v-for="listing in listings.data"
                                :key="listing.id"
                            >
                                <td class="flex gap-1">
                                    <span
                                        :class="
                                            listing.type === 'buy'
                                                ? 'text-red-500'
                                                : 'text-green-500'
                                        "
                                        class="font-bold"
                                    >
                                        [{{
                                            listing.type
                                                .charAt(0)
                                                .toUpperCase()
                                        }}]
                                    </span>

                                    <Tooltip>
                                        <p>{{ formatGold(listing.quantity) }}</p>

                                        <template #popper>
                                            {{ listing.quantity.toLocaleString() }}
                                        </template>
                                    </Tooltip>

                                     for

                                     <Tooltip>
                                        <p>{{ formatGold(listing.price) }}GP ea.</p>

                                        <template #popper>
                                            {{ listing.price.toLocaleString() }}
                                        </template>
                                    </Tooltip>
                                </td>

                                <td class="text-stone-400">
                                    {{
                                        listing.username
                                            .split(" ")
                                            .map(
                                                (word) =>
                                                    word
                                                        .charAt(0)
                                                        .toUpperCase() +
                                                    word.slice(1).toLowerCase(),
                                            )
                                            .join(" ")
                                    }}
                                </td>

                                <td>
                                    <Tooltip>
                                        <p>{{ fromNow(listing.updatedAt) }}</p>

                                        <template #popper>
                                            {{ formatTime(listing.updatedAt) }}
                                        </template>
                                    </Tooltip>
                                </td>

                                <td class="max-w-[110px]">
                                    <div
                                        v-if="listing.canManage"
                                        class="flex flex-nowrap justify-end gap-1"
                                    >
                                        <Link
                                            :href="
                                                route('listings.edit', {
                                                    listing,
                                                })
                                            "
                                            class="w-fit rounded-md bg-amber-300 px-2 py-1 text-amber-900 hover:bg-amber-400"
                                        >
                                            <PencilSquareIcon class="size-5" />
                                        </Link>

                                        <button
                                            class="w-fit rounded-md bg-red-300 px-2 py-1 text-red-900 hover:bg-red-400"
                                            @click="destroy(listing.id)"
                                        >
                                            <TrashIcon class="size-5" />
                                        </button>
                                    </div>

                                    <template v-else>
                                        <Tooltip>
                                            <p class="truncate">
                                                {{ listing.notes }}
                                            </p>

                                            <template #popper>
                                                {{ listing.notes }}
                                            </template>
                                        </Tooltip>
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="listings.data.length" class="px-3">
                        <Pagination class="mt-0" :data="listings" />
                    </div>
                </div>
            </div>

            <form
                class="flex flex-col gap-4 border-2 border-[#382418] bg-black p-3"
                @submit.prevent="submit"
            >
                <h2 class="font-bold">Post a Listing</h2>

                <div
                    v-if="Object.keys(form.errors).length !== 0"
                    class="text-red-500"
                >
                    <p v-for="error in form.errors" :key="error">
                        {{ error }}
                    </p>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-2">
                        <p>I want to</p>

                        <select
                            v-model="form.type"
                            class="border-slate-900 bg-stone-700 py-0 pl-2 placeholder:text-stone-400"
                        >
                            <option
                                v-for="type in listingTypes"
                                :key="type"
                                :value="type"
                            >
                                {{
                                    type.charAt(0).toUpperCase() + type.slice(1)
                                }}
                            </option>
                        </select>

                        <input
                            v-model="form.quantity"
                            type="text"
                            class="w-16 border-slate-900 bg-stone-700 py-0 pl-1 pr-0 placeholder:text-stone-400"
                            placeholder="Qty"
                        />

                        <p>for</p>

                        <input
                            v-model="form.price"
                            type="number"
                            class="w-28 border-slate-900 bg-stone-700 py-0 pl-1 pr-0 placeholder:text-stone-400"
                            placeholder="Price"
                        />

                        <p>GP ea.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <p>My username is</p>

                        <input
                            v-model="form.username"
                            type="text"
                            class="w-28 border-slate-900 bg-stone-700 py-0 pl-1 pr-0 placeholder:text-stone-400"
                            placeholder="Username"
                        />

                        <p>Notes:</p>

                        <input
                            v-model="form.notes"
                            type="text"
                            class="w-48 border-slate-900 bg-stone-700 py-0 pl-1 pr-0 placeholder:text-stone-400"
                            placeholder="Optional, ex: w1 varrock"
                        />
                    </div>

                    <button
                        type="submit"
                        class="w-fit rounded-sm bg-green-800 px-3 py-1 text-white hover:bg-green-700"
                    >
                        Submit
                    </button>
                </div>
            </form>

            <div class="flex flex-col gap-2 border-2 border-[#382418] bg-black">
                <div class="px-3 pt-3">
                    <h2 class="text-lg font-bold">Previous Listings:</h2>
                </div>

                <table class="border-separate border-spacing-2">
                    <tbody>
                        <tr
                            v-for="listing in deletedListings"
                            :key="listing.id"
                        >
                            <td class="text-stone-500">
                                <span
                                    :class="
                                        listing.type === 'buy'
                                            ? 'text-red-800'
                                            : 'text-green-800'
                                    "
                                    class="font-bold"
                                >
                                    [{{ listing.type.charAt(0).toUpperCase() }}]
                                </span>
                                {{ listing.quantity.toLocaleString() }} for
                                {{ listing.price.toLocaleString() }}GP ea.
                            </td>

                            <td class="text-stone-500">
                                {{
                                    listing.username
                                        .split(" ")
                                        .map(
                                            (word) =>
                                                word.charAt(0).toUpperCase() +
                                                word.slice(1).toLowerCase(),
                                        )
                                        .join(" ")
                                }}
                            </td>

                            <td>
                                <Tooltip v-if="listing.deletedAt">
                                    <p>{{ fromNow(listing.deletedAt) }} ago</p>

                                    <template #popper>
                                        {{ formatTime(listing.deletedAt) }}
                                    </template>
                                </Tooltip>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </LayoutMain>
</template>
