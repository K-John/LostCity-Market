<script setup lang="ts">
import { ShareIcon } from "@heroicons/vue/24/outline";

const props = defineProps<Pages.ReferralsIndexPage>();
</script>

<template>
    <LayoutMain>
        <Head title="Referrals" />

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
            <ShareIcon class="size-12 text-emerald-500" />

            <div>
                <h1 class="text-2xl font-semibold">Referrals</h1>

                <p class="text-stone-300">
                    Referral codes used to redirect users to Lost City.
                </p>
            </div>
        </div>

        <hr class="my-5 border-t-2 border-stone-800" />

        <ListingTable
            class="relative overflow-x-auto"
            table-class="border-separate border-spacing-y-2 -mt-2"
        >
            <template #table-header>
                <tr class="bg-stone-950 text-left text-stone-300">
                    <th class="py-2 font-semibold">ID</th>

                    <th class="py-2 font-semibold">Code</th>

                    <th class="py-2 font-semibold">Date</th>
                </tr>
            </template>

            <tr v-for="referral in props.referrals.data" :key="referral.id">
                <td>{{ referral.id }}</td>

                <td class="text-emerald-500">{{ referral.code }}</td>

                <td>
                    {{ formatTime(referral.createdAt) }}
                </td>
            </tr>

            <tr v-if="!props.referrals.data.length">
                <td class="text-center" colspan="3">No referrals found.</td>
            </tr>

            <template v-if="props.referrals.data.length" #footer>
                <Pagination :data="props.referrals" />
            </template>
        </ListingTable>
    </LayoutMain>
</template>
