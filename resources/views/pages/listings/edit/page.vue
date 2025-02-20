<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
const props = defineProps<Pages.ListingsEditPage>();

const listingTypes = computed((): Enums.ListingType[] => ["buy", "sell"]);

const form = useForm({
    ...props.listingForm,
});

const submit = () => {
    form.patch(route("listings.update", { listing: props.listingForm.id }), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <LayoutMain>
        <Head title="Update Listing" />

        <form
            class="flex flex-col gap-4 border-2 border-[#382418] bg-black p-3"
            @submit.prevent="submit"
        >
            <h2 class="font-bold">Update a Listing</h2>

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
                            {{ type.charAt(0).toUpperCase() + type.slice(1) }}
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

                    <template v-if="form.usernames && form.usernames.length">
                        <select
                            v-model="form.username"
                            class="w-28 border-slate-900 bg-stone-700 py-0 pl-1 pr-0 placeholder:text-stone-400"
                        >
                            <option
                                v-for="username in form.usernames"
                                :key="username"
                                :value="username"
                            >
                                {{ toDisplayName(username) }}
                            </option>
                        </select>
                    </template>

                    <template v-else>
                        <input
                            v-model="form.username"
                            type="text"
                            class="w-28 border-slate-900 bg-stone-700 py-0 pl-1 pr-0 placeholder:text-stone-400"
                            placeholder="Username"
                        />
                    </template>

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
    </LayoutMain>
</template>
