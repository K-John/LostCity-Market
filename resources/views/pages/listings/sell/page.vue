<script setup lang="ts">
import { CheckIcon } from "@heroicons/vue/24/outline";
import { Tooltip } from "floating-vue";
import { PlusIcon, XMarkIcon } from "@heroicons/vue/24/solid";

const props = defineProps<Pages.ListingsSalePage>();

const form = useForm({
    ...props.listingSaleForm,
});

const typeVerb = computed(() => {
    return props.listing.type === "buy" ? "bought" : "sold";
});

// Track last added offer index to auto-open its ItemSelect in the child
const lastAutoOpenIndex = ref<number | null>(null);
const addOffer = () => {
    if (!Array.isArray(form.offers)) form.offers = [];
    form.offers.push({
        id: null,
        listingId: null,
        title: "For each item:",
        items: [],
    });
    lastAutoOpenIndex.value = form.offers.length - 1;
    nextTick(() => (lastAutoOpenIndex.value = null));
};
const removeOffer = (offerIndex: number) => {
    form.offers.splice(offerIndex, 1);
    if (form.offers.length === 0)
        form.offers.push({
            id: null,
            listingId: null,
            title: "For each item:",
            items: [],
        });
};

watch(
    () => form.quantity,
    (quantity) => {
        if (quantity === null) return;

        const quantityString = quantity.toString();

        const trimmed = quantityString.trim().toLowerCase();

        const match = trimmed.match(/^([0-9]*\.?[0-9]+)\s*([kmb])?$/);

        if (!match) return null;

        const value = parseFloat(match[1]);
        const suffix = match[2];

        let multiplier = 1;

        switch (suffix) {
            case "k":
                multiplier = 1_000;
                break;
            case "m":
                multiplier = 1_000_000;
                break;
            case "b":
                multiplier = 1_000_000_000;
                break;
        }

        form.quantity = value * multiplier;
    },
);

const submit = (close: () => void) => {
    form.post(route("listing.sale.store", { listing: props.listing.id }), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            close();
        },
    });
};
</script>

<template>
    <BaseModal>
        <template #default="{ close }">
            <Head
                :title="`Mark ${typeVerb.charAt(0).toUpperCase() + typeVerb.slice(1)}`"
            />

            <form class="flex flex-col gap-4" @submit.prevent="submit(close)">
                <div class="flex items-center gap-2">
                    <CheckIcon class="size-7 text-green-500" />

                    <h3 class="text-xl font-semibold">
                        Mark
                        {{
                            typeVerb.charAt(0).toUpperCase() + typeVerb.slice(1)
                        }}
                    </h3>
                </div>

                <p class="text-stone-200">
                    Please confirm the quantity and offer {{ typeVerb }}.
                </p>

                <div v-if="listing.item" class="flex items-center gap-2">
                    <div class="w-fit border border-stone-700 bg-stone-800">
                        <img
                            :src="`/img/items/${listing.item.slug}.webp`"
                            :alt="`${listing.item.name} Icon`"
                            class="min-h-[24px] min-w-[24px]"
                            width="32"
                            height="32"
                        />
                    </div>

                    <span class="text-lg">{{ listing.item.name }}</span>
                </div>

                <div
                    v-if="Object.keys(form.errors).length !== 0"
                    class="text-red-500"
                >
                    <p v-for="(error, key) in form.errors" :key="key">
                        {{ error }}
                    </p>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="quantity" class="font-semibold text-stone-300"
                        >Quantity:</label
                    >

                    <div class="flex items-center gap-4">
                        <input
                            id="quantity"
                            v-model="form.quantity"
                            type="number"
                            class="w-20 rounded-md border border-stone-700 bg-stone-800 px-2 py-1 text-center text-stone-200"
                            min="0"
                            max="listing.quantity"
                            placeholder="Qty"
                        />

                        <SnapSlider
                            v-model="form.quantity"
                            :max="listing.quantity"
                        ></SnapSlider>
                    </div>
                </div>

                <div
                    v-if="form.offers === null || form.offers.length === 0"
                    class="flex flex-col gap-2"
                >
                    <label for="price" class="font-semibold text-stone-300"
                        >Price:</label
                    >

                    <div class="flex items-center gap-2">
                        <input
                            id="price"
                            v-model="form.price"
                            type="number"
                            class="w-28 rounded-md border border-stone-700 bg-stone-800 px-2 py-1 text-end text-stone-200"
                            min="0"
                            placeholder="Price"
                        />

                        <p class="text-stone-300">GP ea.</p>
                    </div>
                </div>

                <div v-else class="flex flex-col gap-2">
                    <span class="font-semibold text-stone-300"
                        >What did you {{ listing.type }}
                        {{ form.quantity > 1 ? "them" : "it" }} for?</span
                    >

                    <div class="flex w-fit flex-col gap-3">
                        <div
                            v-for="(offer, offerIndex) in form.offers"
                            :key="offerIndex"
                            class="flex flex-col gap-2"
                        >
                            <div
                                class="flex w-fit flex-col gap-2 border-2 border-stone-700 bg-stone-950 p-2"
                            >
                                <ListingOffer
                                    v-model="form.offers[offerIndex]"
                                    :listing-type="listing.type"
                                    :auto-open="
                                        lastAutoOpenIndex === offerIndex
                                    "
                                />
                            </div>

                            <div v-if="form.offers.length > 1" class="w-fit">
                                <Tooltip>
                                    <BaseButton
                                        variant="custom"
                                        class="flex h-fit items-center gap-1 bg-stone-800 !px-3 text-sm text-red-500"
                                        @click="removeOffer(offerIndex)"
                                    >
                                        <XMarkIcon class="size-4" />
                                    </BaseButton>

                                    <template #popper> Remove Offer</template>
                                </Tooltip>
                            </div>

                            <div
                                v-if="offerIndex !== form.offers.length - 1"
                                class="flex items-center gap-2"
                            >
                                <div class="h-px grow bg-stone-700"></div>

                                <div class="text-sm font-medium">OR</div>

                                <div class="h-px grow bg-stone-700"></div>
                            </div>
                        </div>
                    </div>

                    <BaseButton
                        class="my-2 flex size-fit items-center gap-1 text-sm"
                        @click="addOffer"
                    >
                        <PlusIcon class="size-4" />
                        Add Another Offer
                    </BaseButton>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <BaseButton variant="success" type="submit">
                        Submit
                    </BaseButton>

                    <BaseButton
                        variant="secondary"
                        type="button"
                        @click="close"
                    >
                        Cancel
                    </BaseButton>
                </div>
            </form>
        </template>
    </BaseModal>
</template>
