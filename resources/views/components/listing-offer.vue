<script setup lang="ts">
import { ref, watch, nextTick, onMounted } from "vue";
import {
    PlusIcon,
    XMarkIcon,
    PencilIcon,
    CheckIcon,
} from "@heroicons/vue/24/solid";
import ListingOfferItem from "./listing-offer-item.vue";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";

const props = withDefaults(
    defineProps<{
        modelValue: Data.Listing.ListingOfferData;
        listingType: Enums.ListingType;
        autoOpen?: boolean;
    }>(),
    {
        autoOpen: true,
    },
);

const emit = defineEmits<{
    (e: "update:modelValue", value: Data.Listing.ListingOfferData): void;
}>();

const isSelectOpen = ref<boolean>(props.autoOpen);
const selected = ref<Data.Item.ItemData | null>(null);
const containerRef = ref<HTMLElement | null>(null);
const lastAddedIndex = ref<number | null>(null);

// Title editing state
const isEditing = ref<boolean>(false);
const editTitle = ref<string>(props.modelValue.title ?? "");
const titleInputRef = ref<HTMLInputElement | null>(null);

watch(
    () => props.autoOpen,
    (val) => {
        if (val) openSelect();
    },
);
onMounted(() => {
    if (props.autoOpen) openSelect();
});

const openSelect = () => {
    isSelectOpen.value = true;
    nextTick(() => {
        const input =
            containerRef.value?.querySelector<HTMLInputElement>(".vs__search");
        input?.focus();
    });
};
const closeSelect = () => {
    isSelectOpen.value = false;
    selected.value = null;
};

watch(selected, (item) => {
    if (!item) return;

    const items = [...props.modelValue.items];
    const exists = items.some((it) => it.item.id === item.id);

    if (!exists) {
        items.push({
            id: null,
            listingOfferId: null,
            item_id: item.id,
            item,
            quantity: 1,
        });
        emit("update:modelValue", { ...props.modelValue, items });
        lastAddedIndex.value = items.length - 1;
    }

    closeSelect();
});

const removeItem = (index: number) => {
    const items = [...props.modelValue.items];
    items.splice(index, 1);

    emit("update:modelValue", { ...props.modelValue, items });
};

const updateQuantity = (index: number, value: number) => {
    const items = [...props.modelValue.items];
    if (!items[index]) return;

    items[index].quantity = value < 1 ? 0 : value;
    emit("update:modelValue", { ...props.modelValue, items });
};

const toggleEdit = () => {
    isEditing.value = !isEditing.value;
    if (isEditing.value) {
        editTitle.value = props.modelValue.title ?? "";
        nextTick(() => {
            titleInputRef.value?.focus();
            titleInputRef.value?.select?.();
        });
    }
};

const saveEdit = () => {
    const title = (editTitle.value ?? "").trim();
    emit("update:modelValue", { ...props.modelValue, title });
    isEditing.value = false;
};

const cancelEdit = () => {
    isEditing.value = false;
    editTitle.value = props.modelValue.title ?? "";
};
</script>

<template>
    <div class="flex flex-col gap-2">
        <div class="flex items-center gap-1 text-sm text-stone-200">
            <span
                v-if="!isEditing"
                class="align-top leading-none"
                @click="toggleEdit"
                >{{ modelValue.title }}</span
            >

            <input
                v-else
                id="title-input"
                ref="titleInputRef"
                v-model="editTitle"
                type="text"
                class="border-slate-900 bg-stone-700 py-0 pl-1 focus:outline-none"
                @keyup.esc="cancelEdit"
                @keypress.enter.prevent="saveEdit"
                @blur="saveEdit"
            />

            <Tooltip v-if="!isEditing">
                <button
                    type="button"
                    class="text-stone-400 hover:text-stone-300"
                    @click="toggleEdit"
                >
                    <PencilIcon class="size-4" />
                </button>

                <template #popper> Edit Title </template>
            </Tooltip>
        </div>

        <div class="flex flex-wrap gap-3">
            <ListingOfferItem
                v-for="(offerItem, itemIndex) in modelValue.items"
                :key="offerItem.item.id + '-' + itemIndex"
                :offer-item="offerItem"
                :auto-focus="lastAddedIndex === itemIndex"
                @update:quantity="
                    (val: number) => updateQuantity(itemIndex, val)
                "
                @remove="removeItem(itemIndex)"
            />

            <template v-if="!isSelectOpen">
                <div class="flex items-center">
                    <Tooltip>
                        <BaseButton variant="success" @click="openSelect">
                            <PlusIcon class="size-4" />
                        </BaseButton>

                        <template #popper> Add Item</template>
                    </Tooltip>
                </div>
            </template>

            <template v-else>
                <div
                    :ref="(el) => (containerRef = el as HTMLElement)"
                    class="flex items-center gap-2"
                >
                    <ItemSelect
                        v-model="selected"
                        :include-banners="false"
                        include-unlisted
                        class="w-52"
                    />

                    <Tooltip>
                        <BaseButton variant="danger" @click="closeSelect">
                            <XMarkIcon class="size-4" />
                        </BaseButton>

                        <template #popper> Cancel</template>
                    </Tooltip>
                </div>
            </template>
        </div>
    </div>
</template>
