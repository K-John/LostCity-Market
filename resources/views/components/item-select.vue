<script setup lang="ts">
import VueSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import _ from "lodash";
import { ref, watch, defineEmits } from "vue";

const props = defineProps<{
    includeBanners?: boolean;
    includeUnlisted?: boolean;
}>();

const emit = defineEmits<{
    (e: "item-selected", value: Data.Item.ItemData): void;
}>();

const selected = ref<Data.Item.ItemData | null>(null);
const options = ref<Data.Item.ItemData[]>([]);

const onSearch = (searchTerm: string, loading: (state: boolean) => void) => {
    if (searchTerm.length > 2) {
        loading(true);
        fetchItems(searchTerm, loading);
    }
};

const fetchItems = _.debounce(
    (search: string, loading: (state: boolean) => void) => {
        fetch(
            `${route("items.index")}?q=${search}${props.includeBanners ? "&include_banners=true" : ""}${props.includeUnlisted ? "&include_unlisted=true" : ""}`,
        )
            .then((response) => response.json())
            .then((data) => {
                loading(false);
                options.value = data;
            });
    },
    200,
);

// Watch selected value and emit change
watch(selected, (newValue) => {
    if (newValue) {
        emit("item-selected", newValue);
    }
});
</script>

<template>
    <VueSelect
        v-model="selected"
        :options="options"
        label="name"
        :filterable="false"
        class="grow rounded-sm text-white"
        @search="onSearch"
    >
        <template #no-options="{ search, searching }">
            <template v-if="searching">
                No results found for <em>{{ search }}</em
                >.
            </template>

            <em v-else class="opacity-75">Search and select an item.</em>
        </template>

        <template #option="option: Data.Item.ItemData">
            <div class="flex items-center gap-2">
                <div class="border border-stone-600 bg-stone-700">
                    <img
                        class="min-h-[32px] min-w-[32px]"
                        :src="`/img/items/${option.slug}.webp`"
                        :alt="`${option.name} Icon`"
                        width="32"
                        height="32"
                    />
                </div>

                <p>{{ option.name }}</p>
            </div>
        </template>

        <template #selected-option="option: Data.Item.ItemData">
            <div class="flex items-center gap-2 text-white">
                <div class="border border-stone-600 bg-stone-700">
                    <img
                        class="min-h-[32px] min-w-[32px]"
                        :src="`/img/items/${option.slug}.webp`"
                        :alt="`${option.name} Icon`"
                        width="32"
                        height="32"
                    />
                </div>

                <p>{{ option.name }}</p>
            </div>
        </template>
    </VueSelect>
</template>

<style>
.vs__search::placeholder,
.vs__dropdown-toggle {
    background: #44403c;
}

.vs__dropdown-menu {
    background: #292524;
}

.vs__clear,
.vs__open-indicator {
    fill: #999191;
}

.vs__dropdown-option--highlight {
    background: rgba(0, 0, 0, 0.2);
}

.vs__dropdown-option {
    padding: 3px 10px;
}
</style>
