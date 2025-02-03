<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";
import {
    PencilSquareIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline/index.js";

const props = defineProps<Pages.ListingsIndexPage>();

const form = useForm({
    ...props.tokenForm,
});

const submit = () => {
    form.post(route("tokens.store"), {
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
        <div
            v-if="!props.token"
            class="mb-4 flex flex-col gap-4 border-2 border-amber-800 bg-amber-950 p-3"
        >
            <p>
                A token will be created for you when you
                <Link
                    :href="route('listings.create')"
                    class="text-[#90c040] hover:underline"
                    >create a listing</Link
                >
                . If you have an existing token, you can sign in with it here.
            </p>

            <form class="flex flex-col gap-4" @submit.prevent="submit">
                <div
                    v-if="Object.keys(form.errors).length !== 0"
                    class="text-white"
                >
                    <p v-for="error in form.errors" :key="error">
                        {{ error }}
                    </p>
                </div>

                <input
                    v-model="form.token"
                    type="text"
                    class="w-full py-0 pl-1 pr-0 text-black"
                />

                <button
                    type="submit"
                    class="w-fit bg-white px-3 text-black hover:bg-slate-200"
                >
                    Submit
                </button>
            </form>
        </div>

        <div
            v-else
            class="mb-4 flex flex-col gap-2 border-2 border-green-800 bg-green-950 p-3"
        >
            <h2 class="font-bold">Save Your Token</h2>

            <p>
                Your are signed in with a token. You can save this token to
                restore access to your listings incase your browser cache is
                reset and you lose the cookie.
            </p>

            <a
                :href="route('tokens.download')"
                target="_blank"
                class="w-fit bg-white px-3 text-black hover:bg-slate-200"
            >
                Download Token
            </a>
        </div>

        <div class="flex flex-col gap-2 border-2 border-[#382418] bg-black">
            <div class="px-3 pt-3">
                <h2 class="text-lg font-bold">My Listings:</h2>
            </div>

            <table class="border-separate border-spacing-2">
                <tbody>
                    <tr v-for="listing in listings.data" :key="listing.id">
                        <td>
                            <Tooltip>
                                <Link
                                    v-if="listing.item"
                                    :href="
                                        route('items.show', {
                                            item: listing.item.slug,
                                        })
                                    "
                                >
                                    <img
                                        :src="`/img/items/${listing.item.slug}.png`"
                                        :alt="`${listing.item.name} Icon`"
                                        width="32"
                                        height="32"
                                    />
                                </Link>

                                <template #popper>
                                    {{
                                        listing.item
                                            ? listing.item.name
                                            : "Unknown Item"
                                    }}
                                </template>
                            </Tooltip>
                        </td>

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

                        <td>
                            <Tooltip>
                                <p>{{ fromNow(listing.updatedAt) }}</p>

                                <template #popper>
                                    {{ formatTime(listing.updatedAt) }}
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

                        <td class="flex flex-nowrap gap-1">
                            <Link
                                :href="route('listings.edit', { listing })"
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
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="px-3">
                <Pagination class="mt-0" :data="listings" />
            </div>
        </div>
    </LayoutMain>
</template>
