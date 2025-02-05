<script setup lang="ts">
import { Head, usePoll } from "@inertiajs/vue3";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";

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

usePoll(5000);

const mostRecentUpdateDate = computed(() => {
    if (!props.listings.data.length) return null;

    return props.listings.data.reduce((latest, listing) => {
        const listingDate = new Date(listing.updatedAt);
        return listing.updatedAt && listingDate > new Date(latest)
            ? listing.updatedAt
            : latest;
    }, props.listings.data[0].updatedAt);
});

watch(mostRecentUpdateDate, (newValue, oldValue) => {
    if (newValue !== oldValue) {
        const newHighlightIds = props.listings.data
            .filter(
                (listing) =>
                    listing.updatedAt &&
                    new Date(listing.updatedAt) > new Date(oldValue as string),
            )
            .map((listing) => listing.id);

        highlightedIds.value.push(...newHighlightIds);
    }
});

const highlightedIds = ref<number[]>([]);
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

                <ListingTable @mouseenter="highlightedIds = []">
                    <EmptyTableRow v-if="!props.listings.data.length" />

                    <ListingTableRow
                        v-for="l in listings.data"
                        :key="l.id"
                        :listing="l"
                        :highlighted="highlightedIds.includes(l.id)"
                    >
                        <template #default="{ listing }">
                            <PriceTableData :listing="listing" />

                            <UsernameTableData :username="listing.username" />

                            <TimestampTableData
                                :timestamp="listing.updatedAt"
                            />

                            <NoteTableData :listing="listing" />
                        </template>
                    </ListingTableRow>

                    <template v-if="listings.data.length" #footer>
                        <Pagination :data="listings" />
                    </template>
                </ListingTable>
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

            <ListingTable>
                <template #header>
                    <h2 class="text-lg font-bold">Previous Listings:</h2>
                </template>

                <EmptyTableRow v-if="!deletedListings.length" />

                <ListingTableRow
                    v-for="l in deletedListings"
                    :key="l.id"
                    :listing="l"
                >
                    <template #default="{ listing }">
                        <PriceTableData
                            :listing="listing"
                            class="text-stone-500"
                        />

                        <UsernameTableData
                            :username="listing.username"
                            class="text-stone-500"
                        />

                        <TimestampTableData
                            :timestamp="listing.deletedAt || ''"
                        />
                    </template>
                </ListingTableRow>
            </ListingTable>
        </div>
    </LayoutMain>
</template>
