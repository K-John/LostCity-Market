<script setup lang="ts">
    import { HeartIcon } from "@heroicons/vue/24/solid/index.js";
</script>

<template>
    <div class="mx-auto w-[600px]">
        <div class="flex">
            <img src="@/img/assets/edge_a.jpg" />

            <img src="@/img/assets/edge_c.jpg" />

            <img src="@/img/assets/edge_d.jpg" />
        </div>

        <div class="bg-[url('@/img/assets/background2.jpg')] px-10">
            <table
                width="250"
                bgcolor="black"
                cellpadding="4"
                class="mx-auto mb-5"
            >
                <tbody>
                    <tr>
                        <td class="border-2 border-[#382418] leading-tight">
                            <center>
                                <b>Lost City Markets</b>

                                <br />

                                <Link
                                    href="/"
                                    class="text-[#90c040] hover:underline"
                                    >Main menu</Link
                                >

                                -
                                <Link
                                    :href="route('listings.index')"
                                    class="text-[#90c040] hover:underline"
                                    >My Listings</Link
                                >
                            </center>
                        </td>
                    </tr>
                </tbody>
            </table>

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
                        <a
                            href="https://github.com/K-John"
                            target="_blank"
                            class="text-[#90c040] hover:underline"
                        >
                            BigShot
                        </a>
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
