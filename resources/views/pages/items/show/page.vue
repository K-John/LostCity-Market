<script setup lang="ts">
const props = defineProps<Pages.Items.ItemsShowPage>();

const listingTypes = computed((): Enums.ListingType[] => ["buy", "sell"]);

const form = useForm({
    ...props.listingForm,
});

const submit = () => {
    form.post(route("listings.store"), {
        onFinish: () => form.reset(),
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
                    </div>

                    <button
                        type="submit"
                        class="w-fit bg-white px-3 text-black hover:bg-slate-200"
                    >
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </LayoutMain>
</template>
