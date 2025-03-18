<script setup lang="ts"></script>

<template>
    <LayoutMain>
        <Head title="Login" />

        <Alert title="You must login with Discord to use Markets">
            <p>
                We integrate directly with
                <a
                    href="https://2004.lostcity.rs/"
                    target="_blank"
                    class="text-[#90c040] hover:underline"
                    >Lost City</a
                >. If you created your
                
                <a
                    href="https://2004.lostcity.rs/"
                    target="_blank"
                    class="text-[#90c040] hover:underline"
                    >Lost City</a
                >
                account before Discord login was required, you must link your
                accounts to continue using Markets.
            </p>

            <p>
                You can find instructions for adopting accounts to Discord by
                <Link
                    :href="route('docs.adopt')"
                    class="text-[#90c040] hover:underline"
                    >clicking here</Link
                >.
            </p>
        </Alert>

        <div class="my-8 flex justify-center">
            <a
                :href="route('auth.discord')"
                class="flex w-fit items-center gap-2 rounded-md bg-[#5865F2] px-4 py-3 text-xl text-white hover:bg-[#8891f2]"
            >
                <DiscordLogo class="size-5" />
                Login with Discord
            </a>
        </div>
    </LayoutMain>
</template>
