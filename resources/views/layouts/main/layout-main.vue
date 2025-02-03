<script lang="ts"></script>

<template>
    <div class="mx-auto w-[600px]">
        <div class="flex">
            <img src="@/img/assets/edge_a.jpg" />

            <img src="@/img/assets/edge_c.jpg" />

            <img src="@/img/assets/edge_d.jpg" />
        </div>

        <div class="bg-[url('@/img/assets/background2.jpg')] px-10">
            <table width="250" bgcolor="black" cellpadding="4" class="mx-auto mb-5">
                <tbody>
                    <tr>
                        <td class="border-2 border-[#382418] leading-tight">
                            <center>
                                <b>Lost City Markets</b>
                                
                                <br />

                                <Link href="/" class="text-[#90c040] hover:underline">Main menu</Link>

                                - <Link :href="route('listings.index')" class="text-[#90c040] hover:underline">My Listings</Link>
                            </center>
                        </td>
                    </tr>
                </tbody>
            </table>

            <slot />
        </div>

        <div class="flex items-end">
            <img src="@/img/assets/edge_g2.jpg" />

            <img src="@/img/assets/edge_c.jpg" class="mb-[-2px]" />

            <img src="@/img/assets/edge_h2.jpg" />
        </div>
    </div>
</template>
