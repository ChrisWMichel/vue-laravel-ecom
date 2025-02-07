<template>
    <div>
        <Loading
            v-model:active="store.isLoading"
            :is-full-page="data.fullpage"
        />
    </div>
</template>

<script setup>
import Loading from "vue-loading-overlay";
import { reactive } from "vue";

const props = defineProps({
    store: Object,
    required: true,
});

const data = reactive({
    fullpage: true,
});
</script>

<style scoped></style>
