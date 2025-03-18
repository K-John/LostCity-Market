<script setup lang="ts"></script>

<template>
    <LayoutMain>
        <Head title="Discord Enforcement" />

        <div class="mx-auto flex max-w-[620px] flex-col gap-4">
            <h1 class="text-2xl font-bold text-stone-300">
                Important Update: Discord Login Now Required
            </h1>

            <div class="flex items-center gap-2">
                <img
                    src="http://github.com/k-john.png?size=40"
                    alt="Discord Enforcement"
                    class="size-7"
                />

                <div class="flex items-center gap-1">
                    <strong class="text-sky-500">BigShot</strong>

                    <span>—</span>

                    <i class="text-stone-400">18th March 2025</i>
                </div>
            </div>

            <div
                class="border-2 border-stone-800 bg-stone-950 p-3 text-stone-200"
            >
                <p>
                    It's been a pleasure to see everyone using the Markets. I
                    truly did not expect it to grow as much as it has. It's
                    mostly gone without a hitch, but there have been a number of
                    issues with impersonation and fake listings. To solve this,
                    Discord login will now be required to create listings.
                </p>

                <h2 class="mt-6 text-xl font-bold text-stone-100">
                    Why the Change?
                </h2>

                <p class="mt-2">
                    I originally created the Markets to provide an easy way for
                    players to trade items. I've allowed anonymous listings
                    under any username to keep things user-friendly and
                    flexible. But as the community has grown, so have the issues
                    with impersonation and fake listings. There have been a lot
                    of reports of players being impersonated, and items being
                    falsely advertised. This is not what I want for the Markets.
                </p>

                <p class="mt-2">
                    On Feb 18th,
                    <a
                        href="https://2004.lostcity.rs"
                        target="_blank"
                        class="text-[#90c040] hover:underline"
                        >Lost City</a
                    >
                    started requiring new players to
                    <a
                        href="https://2004.lostcity.rs/news/192"
                        target="_blank"
                        class="text-[#90c040] hover:underline"
                        >authenticate their accounts through Discord</a
                    >. Shortly after, I introduced an option to log in with
                    Discord on the Markets, allowing users to verify their
                    identity to prevent impersonations. This was a good first
                    step, but it hasn't been enough.
                </p>

                <h2 class="mt-6 text-xl font-bold text-stone-100">
                    What This Means for You
                </h2>

                <ul class="mt-3 list-inside list-disc">
                    <li>
                        If you've already linked your Discord account, you don't
                        need to do anything—your listings will continue as
                        usual.
                    </li>

                    <li>
                        New listings will require users to log in via Discord
                        before posting.
                    </li>

                    <li>
                        Existing anonymous listings will remain visible but
                        won't be editable or bumpable without logging in.
                    </li>
                </ul>

                <h2 class="mt-6 text-xl font-bold text-stone-100">
                    How to Log In
                </h2>

                <p class="mt-2">
                    If you've already linked your Discord account with your Lost
                    City account, you just need to log in. If you haven't linked
                    your accounts yet, you can find instructions for adopting
                    accounts to Discord by
                    <a
                        href="https://2004.lostcity.rs/docs/adopt"
                        target="_blank"
                        class="text-[#90c040] hover:underline"
                        >clicking here</a
                    >.
                </p>

                <p class="mt-4 font-medium">
                    I appreciate your support as I continue to improve the Lost
                    City Markets. If you have any questions, feel free to reach
                    out to me on the
                    <a
                        href="https://lostcity.rs/u/bigshot/"
                        target="_blank"
                        class="text-[#90c040] hover:underline"
                        >Lost City Forums</a
                    >. Happy trading!
                </p>
            </div>
        </div>
    </LayoutMain>
</template>
