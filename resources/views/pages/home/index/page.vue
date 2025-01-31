<script setup lang="ts">
import VueSelect from "vue-select";
import _ from "lodash";

const selected = ref(null);
const options = ref([]);

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
    </LayoutMain>
</template>
