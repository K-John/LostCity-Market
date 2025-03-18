<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";

const props = defineProps<{
    listingForm: any;
    submitRoute: string;
    submitMethod?: "post" | "put" | "patch";
}>();

const form = useForm({
    ...props.listingForm,
});

const listingTypes = computed((): Enums.ListingType[] => ["buy", "sell"]);

const setItem = (item: Data.Item.ItemData) => {
    form.item = item;
};

const submit = () => {
    form[props.submitMethod ?? "post"](props.submitRoute, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <form
        class="flex flex-col gap-4 border-2 border-[#382418] bg-black p-3"
        @submit.prevent="submit"
    >
        <h2 class="font-bold">
            {{ props.submitMethod === "patch" ? "Edit" : "Post" }} a Listing
        </h2>

        <div v-if="Object.keys(form.errors).length !== 0" class="text-red-500">
            <p v-for="(error, key) in form.errors" :key="key">
                {{ error }}
            </p>
        </div>

        <!-- Conditionally Hide Item Select if form.item was Provided on Page Load -->
        <div
            v-if="!props.listingForm.item"
            class="flex flex-col gap-x-2 sm:flex-row sm:items-center"
        >
            <p>Search for an item:</p>

            <ItemSelect @item-selected="setItem" />
        </div>

        <div class="flex flex-col gap-3">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <div class="flex items-center gap-2">
                    <p>I want to</p>

                    <select
                        v-model="form.type"
                        class="border-slate-900 bg-stone-700 py-0 pl-2"
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
                        class="w-16 border-slate-900 bg-stone-700 py-0 pl-1"
                        placeholder="Qty"
                    />
                </div>

                <div class="flex items-center gap-2">
                    <p>for</p>

                    <input
                        v-model="form.price"
                        type="number"
                        class="w-28 border-slate-900 bg-stone-700 py-0 pl-1"
                        placeholder="Price"
                    />

                    <p>GP ea.</p>
                </div>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <div class="flex items-center gap-2">
                    <p>My username is</p>

                    <template v-if="form.usernames?.length">
                        <select
                            v-model="form.username"
                            class="w-28 border-slate-900 bg-stone-700 py-0 pl-1"
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
                        <p class="bg-red-900 px-1 py-0 text-red-200">
                            ERROR: No usernames found
                        </p>
                    </template>
                </div>

                <div class="flex items-center gap-2">
                    <p>Notes:</p>

                    <input
                        v-model="form.notes"
                        type="text"
                        class="w-48 border-slate-900 bg-stone-700 py-0 pl-1"
                        placeholder="Optional, ex: w1 varrock"
                    />
                </div>
            </div>

            <button
                type="submit"
                class="w-fit rounded-sm bg-green-800 px-3 py-1 text-white hover:bg-green-700"
            >
                Submit
            </button>
        </div>
    </form>
</template>
