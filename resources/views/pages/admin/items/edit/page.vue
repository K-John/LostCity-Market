<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { PencilIcon } from "@heroicons/vue/24/outline";

const props = defineProps<Pages.Admin.ItemsEditPage>();

const auth = useAuth();
const form = useForm({
    ...props.itemForm,
});

const back = () => {
    window.history.back();
};

const submit = (close: () => void) => {
    form["patch"](route("admin.items.update", { item: props.item }), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            close();
        },
    });
};
</script>

<template>
    <BaseModal>
        <template #default="{ close }">
            <Head title="Edit Item" />

            <div class="flex items-center gap-2">
                <PencilIcon class="size-7 text-amber-500" />

                <h1 class="text-xl font-semibold">Edit Item</h1>
            </div>

            <p class="text-stone-300">
                Update the item details below and submit the form.
            </p>

            <div class="flex items-center gap-2">
                <div class="w-fit border border-stone-700 bg-stone-800">
                    <img
                        :src="`/img/items/${item.slug}.webp`"
                        :alt="`${item.name} Icon`"
                        class="min-h-[24px] min-w-[24px]"
                        width="32"
                        height="32"
                    />
                </div>

                <span class="text-lg">{{ item.name }}</span>
            </div>

            <form class="flex flex-col gap-6" @submit.prevent="submit(close)">
                <div
                    class="flex flex-col gap-4 border-2 border-[#382418] bg-black p-3"
                >
                    <div
                        v-if="Object.keys(form.errors).length !== 0"
                        class="text-red-500"
                    >
                        <p v-for="(error, key) in form.errors" :key="key">
                            {{ error }}
                        </p>
                    </div>

                    <div>
                        <label for="name" class="font-semibold text-stone-300">
                            Name:
                        </label>

                        <input
                            v-model="form.name"
                            type="text"
                            class="w-16 border-slate-900 bg-stone-700 py-0 pl-1"
                            placeholder="Name"
                        />
                    </div>

                    <div>
                        <label for="slug" class="font-semibold text-stone-300">
                            Slug
                        </label>

                        <input
                            v-model="form.slug"
                            type="text"
                            class="w-16 border-slate-900 bg-stone-700 py-0 pl-1"
                            placeholder="Slug"
                        />
                    </div>

                    <div>
                        <label for="cost" class="font-semibold text-stone-300">
                            Cost
                        </label>

                        <input
                            v-model="form.cost"
                            type="number"
                            class="w-16 border-slate-900 bg-stone-700 py-0 pl-1"
                        />
                    </div>

                    <div>
                        <label
                            for="isActive"
                            class="font-semibold text-stone-300"
                        >
                            Is Active
                        </label>

                        <input
                            v-model="form.is_active"
                            type="checkbox"
                            class="size-4 border-slate-900 bg-stone-700 py-0 pl-1"
                        />
                    </div>

                    <div>
                        <label
                            for="isListable"
                            class="font-semibold text-stone-300"
                        >
                            Is Listable
                        </label>

                        <input
                            v-model="form.is_listable"
                            type="checkbox"
                            class="size-4 border-slate-900 bg-stone-700 py-0 pl-1"
                        />
                    </div>
                </div>

                <div class="flex flex-wrap justify-end gap-3">
                    <BaseButton type="submit" variant="success">
                        Submit
                    </BaseButton>

                    <BaseButton
                        type="button"
                        variant="secondary"
                        @click="close()"
                    >
                        Cancel
                    </BaseButton>
                </div>
            </form>
        </template>
    </BaseModal>
</template>
