<script setup lang="ts">
const auth = useAuth();
</script>

<template>
    <LayoutMain>
        <Head title="Adopt Accounts to Discord" />

        <div class="mx-auto flex max-w-[620px] flex-col gap-y-4">
            <div>
                <p class="text-xl font-bold text-stone-500">How To:</p>

                <h2 class="text-3xl font-bold text-stone-300">
                    Adopt Accounts to Discord
                </h2>

                <p class="text-lg text-stone-400">
                    A step-by-step guide to linking your Lost City legacy
                    accounts with Discord.
                </p>
            </div>

            <Alert title="Disclaimer:" type="info">
                This guide is not related to Markets. It is solely intended to
                assist you in linking your Lost City accounts to Discord.
            </Alert>

            <div>
                <h3 class="text-lg font-bold text-stone-400">Step 1:</h3>

                <p>
                    Go to the
                    <a
                        href="https://2004.lostcity.rs/account/login"
                        target="_blank"
                        class="text-[#90c040] hover:underline"
                        >Lost City login page
                    </a>

                    (<a
                        href="https://2004.lostcity.rs/account/login"
                        target="_blank"
                        class="text-xs text-[#90c040] hover:underline"
                        ><i>https://2004.lostcity.rs/account/login</i>
                    </a>

                    ) and click '<u>Continue with Discord</u>'.
                </p>
            </div>

            <Alert>
                <img
                    src="/img/docs/LostCity-Login-Page.webp"
                    alt="Lost City Login Page"
                    class="shadow-sm"
                />
            </Alert>

            <div>
                <h3 class="text-lg font-bold text-stone-400">Step 2:</h3>

                <p>
                    You will be redirected to Discord's login page. Enter your
                    Discord credentials and click '<u>Authorize</u>'.
                </p>
            </div>

            <Alert>
                <img
                    src="/img/docs/Discord-Authorize-LostCity.webp"
                    alt="Discord Authorize Lost City"
                    class="shadow-sm"
                />
            </Alert>

            <div>
                <h3 class="text-lg font-bold text-stone-400">Step 3:</h3>

                <p>
                    You will be redirected back to Lost City. You should now be
                    logged in with Discord. Fill out the 'Adopt a legacy
                    character' form and click '<u>Adopt</u>'.
                </p>
            </div>

            <Alert>
                <img
                    src="/img/docs/LostCity-Adopt-Form.webp"
                    alt="Lost City Adopt Form"
                    class="shadow-sm"
                />
            </Alert>

            <div>
                <h3 class="text-lg font-bold text-stone-400">Step 4:</h3>

                <p>
                    Your character should be linked to your Discord and show up
                    under 'Characters'.
                </p>
            </div>

            <Alert>
                <img
                    src="/img/docs/LostCity-Adopted-Characters.webp"
                    alt="Lost City Adopted Characters"
                    class="shadow-sm"
                />
            </Alert>

            <div>
                <h3 class="text-lg font-bold text-stone-400">Step 5:</h3>

                <p>
                    All done! You can now set your Markets usernames by
                    <Link
                        v-if="!auth"
                        :href="route('login')"
                        class="text-[#90c040] hover:underline"
                        >logging in with Discord</Link
                    >

                    <button
                        v-else
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
                        clicking here</button
                    >.
                </p>
            </div>
        </div>
    </LayoutMain>
</template>
