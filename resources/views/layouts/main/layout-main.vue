<script setup lang="ts">
import { HeartIcon } from "@heroicons/vue/24/solid/index.js";

const auth = useAuth();
</script>

<template>
    <div class="mx-auto w-[600px]">
        <div class="flex">
            <img src="@/img/assets/edge_a.jpg" />

            <img src="@/img/assets/edge_c.jpg" />

            <img src="@/img/assets/edge_d.jpg" />
        </div>

        <div class="bg-[url('@/img/assets/background2.jpg')] px-10">
            <Alert id="discord-integration-notice" type="warning">
                <p class="text-sm">
                    Official username integration is now supported with
                    <Link
                        :href="route('login.index')"
                        class="text-[#90c040] hover:underline"
                        >Discord Login</Link
                    >. This will prevent people from creating listings with your
                    username. It is optional for now, but it will be
                    <strong>required on March 1</strong>.
                </p>
            </Alert>

            <div
                class="mx-auto mb-5 w-fit min-w-[250px] border-2 border-[#382418] bg-black p-1 text-center"
            >
                <h1 class="font-bold">Lost City Markets</h1>

                <div class="flex flex-row items-center justify-center gap-1">
                    <Link href="/" class="text-[#90c040] hover:underline"
                        >Main menu</Link
                    >

                    -

                    <Link
                        :href="route('listings.create')"
                        class="text-[#90c040] hover:underline"
                        >Create Listing</Link
                    >

                    -

                    <Link
                        :href="route('listings.index')"
                        class="text-[#90c040] hover:underline"
                        >My Listings</Link
                    >

                    -

                    <Link
                        v-if="!auth"
                        :href="route('login.index')"
                        class="text-[#90c040] hover:underline"
                        >Login</Link
                    >

                    <button
                        v-else
                        type="button"
                        class="text-[#90c040] hover:underline"
                        @click="
                            router.delete(
                                route('login.destroy', {
                                    login: auth.email,
                                    preserveScroll: true,
                                }),
                            )
                        "
                    >
                        Logout
                    </button>
                </div>
            </div>

            <slot />

            <div
                class="mt-4 flex flex-row items-center justify-between gap-4 border-2 border-[#382418] bg-black p-3 text-sm"
            >
                <p>
                    Fan project. Not affiliated with
                    <a
                        href="https://2004.lostcity.rs"
                        target="_blank"
                        class="text-[#90c040] hover:underline"
                        >Lost City</a
                    >
                </p>

                <div>
                    <a
                        href="https://github.com/K-John/LostCity-Market"
                        target="_blank"
                        class="flex justify-end gap-2 text-[#90c040] hover:underline"
                        ><GithubLogo class="size-5" /> Source Code</a
                    >

                    <p class="flex gap-1">
                        Made by
                        <span class="text-[#90c040]"> BigShot </span>
                        with love
                        <HeartIcon class="size-5 text-red-500" />
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-end">
            <img src="@/img/assets/edge_g2.jpg" />

            <img src="@/img/assets/edge_c.jpg" class="mb-[-2px]" />

            <img src="@/img/assets/edge_h2.jpg" />
        </div>
    </div>
</template>
