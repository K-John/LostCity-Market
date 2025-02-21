<script setup lang="ts"></script>

<template>
    <LayoutMain>
        <Head title="Login" />

        <Alert title="Login with Discord" type="info">
            <p>
                You can login with Discord to reserve and prevent others from
                using your username(s).
            </p>

            <p>
                In order for this to work, you must have already
                <a
                    href="https://2004.lostcity.rs/account"
                    target="_blank"
                    class="text-[#90c040] hover:underline"
                    >logged in with Discord on Lost City</a
                > and adopted your accounts.
            </p>
        </Alert>

        <div class="mb-8 flex justify-center">
            <a
                :href="route('auth.discord')"
                class="flex w-fit items-center gap-2 rounded-sm bg-[#5865F2] px-3 py-2 text-white hover:bg-[#8891f2]"
            >
                <DiscordLogo class="size-5" />
                Login with Discord
            </a>
        </div>
    </LayoutMain>
</template>
