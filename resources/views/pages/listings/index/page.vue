<script setup lang="ts">
import { router, Head } from "@inertiajs/vue3";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";
import {
    PencilSquareIcon,
    CheckIcon,
    ArrowTrendingUpIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline/index.js";

const props = defineProps<Pages.ListingsIndexPage>();

const auth = useAuth();
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

const canBumpListings = computed(() =>
    props.listings.data.some(
        (listing) =>
            new Date(listing.updatedAt) < new Date(Date.now() - 30 * 60 * 1000),
    ),
);
</script>

<template>
    <LayoutMain>
        <Head title="My Listings" />

        <Alert v-if="!auth && !props.token" type="info">
            <p>
                If you have an existing token, you can sign in with it here.
                Otherwise, you should
                <Link
                    :href="route('login.index')"
                    class="text-[#90c040] hover:underline"
                    >login with Discord</Link
                >
                to manage your listings.
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
        </Alert>

        <Alert
            v-if="!auth && props.token"
            id="token-save-notice"
            type="success"
        >
            <h2 class="font-bold">Save Your Token</h2>

            <p>
                Your are signed in with a token. You can save this token to
                restore access to your listings, or you can
                <Link
                    :href="route('login.index')"
                    class="text-[#90c040] hover:underline"
                    >login with Discord</Link
                >.
            </p>

            <a
                :href="route('tokens.download')"
                target="_blank"
                class="w-fit bg-white px-3 text-black hover:bg-slate-200"
            >
                Download Token
            </a>
        </Alert>

        <UsernamesAlert v-if="auth && !usernames?.length" />

        <Alert v-if="auth">
            <div class="flex items-center gap-2">
                <h2 class="font-bold text-stone-200">My Usernames:</h2>

                <span v-if="usernames.length" class="whitespace-pre text-stone-300">
                    {{ usernames.map(toDisplayName).join(", ") }}
                </span>

                <span v-else class="text-stone-400">None</span>
            </div>

            <hr class="border-stone-700" />

            <p class="text-sm text-stone-400">
                Usernames not accurate?
                <button
                    type="button"
                    class="text-[#90c040] hover:underline"
                    @click="
                        router.patch(
                            route('usernames.update', {
                                user: auth.email,
                                preserveScroll: true,
                            }),
                        )
                    "
                >
                    Click here
                </button>
                to get your latest usernames from Lost City.
            </p>
        </Alert>

        <div class="flex flex-col gap-2 border-2 border-[#382418] bg-black">
            <div class="flex justify-between px-3 pt-3">
                <h2 class="text-lg font-bold">My Listings:</h2>

                <Tooltip>
                    <button
                        type="button"
                        class="flex items-center gap-2 rounded-sm bg-stone-800 px-2 py-1 text-amber-400"
                        :class="{
                            'hover:bg-stone-900': canBumpListings,
                            'cursor-not-allowed opacity-50': !canBumpListings,
                        }"
                        :disabled="!canBumpListings"
                        @click="
                            router.patch(route('bump'), {
                                preserveScroll: true,
                            })
                        "
                    >
                        <ArrowTrendingUpIcon class="size-5" /> Bump All
                    </button>

                    <template #popper>
                        <template v-if="!canBumpListings">
                            You have no listings eligible for bumping
                        </template>

                        <template v-else>
                            Listings can be bumped every 30 mins
                        </template>
                    </template>
                </Tooltip>
            </div>

            <table class="border-separate border-spacing-2">
                <tbody>
                    <tr v-if="!listings.data.length">
                        <td class="text-center" colspan="4">
                            No listings found.
                        </td>
                    </tr>

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

                        <td class="flex gap-1">
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

                        <td>
                            <DropdownMenu>
                                <DropdownItem
                                    :icon="ArrowTrendingUpIcon"
                                    text-color="text-amber-400"
                                    @click="
                                        router.patch(
                                            route('listings.bump', {
                                                listing: listing,
                                            }),
                                            { preserveScroll: true },
                                        )
                                    "
                                >
                                    Bump
                                </DropdownItem>

                                <DropdownItem
                                    :icon="CheckIcon"
                                    text-color="text-green-500"
                                    @click="
                                        router.delete(
                                            route('listings.destroy', {
                                                listing: listing.id,
                                            }),
                                            { preserveScroll: true },
                                        )
                                    "
                                >
                                    Mark Sold
                                </DropdownItem>

                                <DropdownItem
                                    :icon="PencilSquareIcon"
                                    text-color="text-amber-500"
                                    :href="route('listings.edit', { listing })"
                                >
                                    Edit
                                </DropdownItem>

                                <DropdownItem
                                    :icon="XMarkIcon"
                                    text-color="text-red-500"
                                    @click="
                                        router.delete(
                                            route('listings.destroy', {
                                                listing: listing.id,
                                                force: true,
                                            }),
                                            { preserveScroll: true },
                                        )
                                    "
                                >
                                    Remove
                                </DropdownItem>
                            </DropdownMenu>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="listings.data.length" class="px-3">
                <Pagination class="mt-0" :data="listings" />
            </div>
        </div>
    </LayoutMain>
</template>
