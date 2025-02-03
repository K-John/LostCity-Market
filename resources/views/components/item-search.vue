<script setup lang="ts">
import VueSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import _ from "lodash";

const selected = ref<Data.Item.ItemData | null>(null);
const options = ref<Data.Item.ItemData[]>([]);
const loading = ref(false);

const onSearch = (searchTerm: string, loading: (state: boolean) => void) => {
    if (searchTerm.length > 2) {
        loading(true);
        search(searchTerm, loading);
    }
};

const search = _.debounce(
    (search: string, loading: (state: boolean) => void) => {
        fetch(`${route("items.index")}?q=${search}`)
            .then((response) => response.json())
            .then((data) => {
                loading(false);
                options.value = data;
            });
    },
    200,
);

watch(selected, (value) => {
    if (value) {
        loading.value = true;
        router.get(route("items.show", { item: value.slug }));
    }
});
</script>

<template>
    <div
        class="mb-4 flex flex-row gap-4 border-2 border-[#382418] bg-black p-3"
    >
        <h2 class="text-lg font-bold">Search for an item:</h2>

        <VueSelect
            v-model="selected"
            :options="options"
            label="name"
            :filterable="false"
            class="grow bg-white text-black"
            @search="onSearch"
        >
        </VueSelect>
    </div>
</template>
