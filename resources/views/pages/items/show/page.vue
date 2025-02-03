<script setup lang="ts">
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
</script>

<template>
    <LayoutMain>
        <div class="flex flex-col gap-6">
            <div class="flex items-center gap-4">
                <div class="border-2 border-[#382418] bg-black p-1">
                    <img
                        :src="`/img/items/${item.slug}.png`"
                        :alt="`${item.name} Icon`"
                        width="32"
                        height="32"
                    />
                </div>

                <h1 class="text-lg font-bold">
                    {{ item.name }}
                </h1>
            </div>

            <form
                class="flex flex-col gap-4 border-2 border-[#382418] bg-black p-3"
                @submit.prevent="submit"
            >
                <h2 class="font-bold">Post a Listing</h2>

                <div v-if="Object.keys(form.errors).length !== 0" class="text-red-500">
                    <p v-for="error in form.errors" :key="error">
                        {{ error }}
                    </p>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-2">
                        <p>I want to</p>

                        <select
                            v-model="form.type"
                            class="py-0 pl-2 text-black"
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
                            class="w-16 py-0 pl-1 pr-0 text-black"
                            placeholder="Qty"
                        />

                        <p>for</p>

                        <input
                            v-model="form.price"
                            type="number"
                            class="w-28 py-0 pl-1 pr-0 text-black"
                            placeholder="Price"
                        />

                        <p>GP ea.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <p>My username is</p>

                        <input
                            v-model="form.username"
                            type="text"
                            class="w-28 py-0 pl-1 pr-0 text-black"
                            placeholder="Username"
                        />

                        <p>Notes:</p>

                        <input
                            v-model="form.notes"
                            type="text"
                            class="w-48 py-0 pl-1 pr-0 text-black"
                            placeholder="Optional, ex: w1 varrock"
                        />
                    </div>

                    <button
                        type="submit"
                        class="w-fit bg-white px-3 text-black hover:bg-slate-200"
                    >
                        Submit
                    </button>
                </div>
            </form>

            <div class="flex flex-col gap-2 border-2 border-[#382418] bg-black">
                <div class="px-3 pt-3">
                    <h2 class="text-lg font-bold">Recent Listings:</h2>
                </div>

                <table class="border-separate border-spacing-2">
                    <tbody>
                        <tr v-for="listing in listings.data" :key="listing.id">
                            <td>
                                <span
                                    :class="
                                        listing.type === 'buy'
                                            ? 'text-red-500'
                                            : 'text-green-500'
                                    "
                                    class="font-bold"
                                >
                                    [{{ listing.type.charAt(0).toUpperCase() }}]
                                </span>
                                {{ listing.quantity.toLocaleString() }} for
                                {{ listing.price.toLocaleString() }}GP ea.
                            </td>

                            <td class="text-stone-400">
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
                                <Tooltip>
                                    <p>{{ fromNow(listing.createdAt) }}</p>

                                    <template #popper>
                                        {{ formatTime(listing.createdAt) }}
                                    </template>
                                </Tooltip>
                            </td>

                            <td class="max-w-[110px]">
                                <Tooltip>
                                    <p class="truncate">{{ listing.notes }}</p>

                                    <template #popper>
                                        {{ listing.notes }}
                                    </template>
                                </Tooltip>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="px-3">
                    <Pagination class="mt-0" :data="listings" />
                </div>
            </div>
        </div>
    </LayoutMain>
</template>
