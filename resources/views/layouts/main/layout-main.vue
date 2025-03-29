<script setup lang="ts">
import {
    HeartIcon,
    ExclamationTriangleIcon,
} from "@heroicons/vue/24/solid/index.js";
import { ref, onMounted, onBeforeUnmount, nextTick } from "vue";
import chainImage from "@/img/assets/edge_chain.jpg";

const auth = useAuth();

const topLeftChainContainer = ref<HTMLElement | null>(null);
const topRightChainContainer = ref<HTMLElement | null>(null);
const bottomLeftChainContainer = ref<HTMLElement | null>(null);
const bottomRightChainContainer = ref<HTMLElement | null>(null);
const chainWidth = 41; // Approximate width of a single chain link

const updateChainLinks = () => {
    if (
        !topLeftChainContainer.value ||
        !topRightChainContainer.value ||
        !bottomLeftChainContainer.value ||
        !bottomRightChainContainer.value
    )
        return;

    // Get the total available width for both chain sections
    const totalWidth =
        topLeftChainContainer.value.clientWidth +
        topRightChainContainer.value.clientWidth;

    let totalChains = Math.floor(totalWidth / chainWidth);
    let leftChains = Math.floor(totalChains / 2);
    let rightChains = totalChains - leftChains; // Ensure total remains the same

    // Function to populate chains dynamically
    const populateChains = (container: HTMLElement, count: number) => {
        container.innerHTML = ""; // Clear previous images

        for (let i = 0; i < count; i++) {
            const img = document.createElement("img");
            img.src = chainImage;
            img.classList.add("w-[41px]", "h-[20px]", "flex-1");
            container.appendChild(img);
        }
    };

    populateChains(topLeftChainContainer.value, leftChains);
    populateChains(topRightChainContainer.value, rightChains);

    populateChains(bottomLeftChainContainer.value, leftChains);
    populateChains(bottomRightChainContainer.value, rightChains);
};

onMounted(async () => {
    await nextTick();
    updateChainLinks();
    window.addEventListener("resize", updateChainLinks);
});

onBeforeUnmount(() => {
    window.removeEventListener("resize", updateChainLinks);
});

const links = computed(() => [
    { label: "Main Menu", href: "/" },
    { label: "Create Listing", href: route("listings.create") },
    ...(auth.value
        ? [{ label: "My Listings", href: route("listings.index") }]
        : []),
    ...(auth.value
        ? [
              {
                  label: "Logout",
                  action: () =>
                      router.delete(
                          route("logout", {
                              login: auth.value.name,
                              preserveScroll: true,
                          }),
                      ),
              },
          ]
        : [{ label: "Login", href: route("login") }]),
]);
</script>

<template>
    <div class="mx-auto max-w-4xl">
        <div class="flex w-full">
            <img class="h-[43px] w-[62px]" src="@/img/assets/edge_a.jpg" />

            <div
                ref="topLeftChainContainer"
                class="mt-[5px] flex grow justify-center"
            ></div>

            <img
                class="h-[30px] w-[164px]"
                src="@/img/assets/edge_middle.jpg"
            />

            <div
                ref="topRightChainContainer"
                class="mt-[5px] flex grow justify-center"
            ></div>

            <img class="h-[43px] w-[62px]" src="@/img/assets/edge_d.jpg" />
        </div>

        <div class="relative flex w-full">
            <div
                class="w-[36px] bg-[url('@/img/assets/background-left.jpg')] bg-repeat-y"
            ></div>

            <div
                class="flex-1 bg-[url('@/img/assets/background-middle.jpg')] bg-contain bg-repeat-y"
            >
                <!-- <Alert id="discord-enforced-notice" type="error">
                    <h2 class="flex gap-2 font-bold">
                        <ExclamationTriangleIcon class="size-6 text-red-300" />

                        Discord Login Required
                    </h2>

                    <p>
                        As of March 18th, official username integration is now
                        enforced. In order to create listings, you must
                        <Link
                            :href="route('login')"
                            class="text-[#90c040] hover:underline"
                            >login with Discord</Link
                        >.
                    </p>

                    <p>
                        Read more about in
                        <Link
                            :href="route('news.discord-enforce')"
                            class="text-[#90c040] hover:underline"
                            >the news article here</Link
                        >
                    </p>
                </Alert>-->

                <div
                    class="mx-auto mb-5 w-fit min-w-[250px] border-2 border-[#382418] bg-black p-1 text-center"
                >
                    <h1 class="font-bold">Lost City Markets</h1>

                    <div
                        class="flex flex-row flex-wrap items-center justify-center gap-1"
                    >
                        <template v-for="(link, index) in links" :key="index">
                            <Link
                                v-if="link.href"
                                :href="link.href"
                                class="text-[#90c040] hover:underline"
                            >
                                {{ link.label }}
                            </Link>

                            <button
                                v-else
                                type="button"
                                class="text-[#90c040] hover:underline"
                                @click="link.action"
                            >
                                {{ link.label }}
                            </button>

                            <!-- Add a hyphen unless it's the last link -->
                            <span v-if="index < links.length - 1">-</span>
                        </template>
                    </div>
                </div>

                <slot />

                <div
                    class="mt-4 flex flex-col items-center justify-between gap-4 border-2 border-stone-800 bg-stone-950 p-3 text-sm sm:flex-row"
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
                            class="flex justify-center gap-2 text-[#90c040] hover:underline sm:justify-end"
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

            <div
                class="w-[36px] bg-[url('@/img/assets/background-right.jpg')] bg-repeat-y"
            ></div>
        </div>

        <div class="flex w-full items-end">
            <img class="h-[77px] w-[100px]" src="@/img/assets/edge_g2.jpg" />

            <div
                ref="bottomLeftChainContainer"
                class="mb-[15px] flex grow justify-center"
            ></div>

            <img
                class="mb-[10px] h-[30px] w-[164px]"
                src="@/img/assets/edge_middle.jpg"
            />

            <div
                ref="bottomRightChainContainer"
                class="mb-[15px] flex grow justify-center"
            ></div>

            <img class="h-[77px] w-[100px]" src="@/img/assets/edge_h2.jpg" />
        </div>
    </div>
</template>
