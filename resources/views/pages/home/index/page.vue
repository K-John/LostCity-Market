<script setup lang="ts">
import VueSelect from "vue-select";
import _ from "lodash";

// selected type of item
const selected = ref<Data.Item.ItemData | null>(null);
const options = ref<Data.Item.ItemData[]>([]);
const loading = ref(false);

const onSearch = (searchTerm: string, loading: (state: boolean) => void) => {
    if (searchTerm.length > 2) {
        loading(true);
        search(searchTerm, loading);
    }
};

const search = _.debounce((search: string, loading: (state: boolean) => void) => {
    fetch(`${route("items.index")}?q=${search}`)
        .then((response) => response.json())
        .then((data) => {
            loading(false);
            options.value = data;
        });
}, 100);

watch(selected, (value) => {
    if (value) {
        loading.value = true;
        window.location.href = route("items.show", { item: value.slug });
    }
});

</script>

<template>
    <LayoutMain>
        <div>
            <VueSelect
                v-model="selected"
                :options="options"
                label="name"
                :filterable="false"
                @search="onSearch"
            >
            </VueSelect>
        </div>
        
        <span v-if="loading">Loading...</span>
    </LayoutMain>
</template>
