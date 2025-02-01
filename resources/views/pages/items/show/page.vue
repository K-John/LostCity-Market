<script setup lang="ts">
const props = defineProps<Pages.Items.ItemsShowPage>();

const form = useForm({
    ...props.listingForm,
    item_id: props.item.id,
});
</script>

<template>
    <LayoutMain>
        <div class="display-flex">
            <img :src="`/img/items/${item.slug}.png`" :alt="`${item.name} Icon`">

            <h1>
                {{ item.name }}
            </h1>
        </div>
    </LayoutMain>
</template>