<script setup lang="ts">
import VueSelect from "vue-select";
import _ from "lodash";
import 'vue-select/dist/vue-select.css';

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
}, 200);

watch(selected, (value) => {
    if (value) {
        loading.value = true;
        router.get(route("items.show", { item: value.slug }));
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
                class="bg-white text-black"
                @search="onSearch"
            >
            </VueSelect>
        </div>
        
        <span v-if="loading">Loading...</span>
    </LayoutMain>
</template>
