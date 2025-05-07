<script setup lang="ts">
import { CheckIcon } from "@heroicons/vue/24/outline";

const props = defineProps<Pages.ListingsSalePage>();

const form = useForm({
    ...props.listingSaleForm,
});

const typeVerb = computed(() => {
    return props.listing.type === "buy" ? "bought" : "sold";
});

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
                    Please confirm the quantity and price {{ typeVerb }}.
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

                <div class="flex flex-col gap-2">
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

                <div class="flex items-center justify-end gap-4">
                    <BaseButton
                        variant="success"
                        type="submit"
                    >
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
