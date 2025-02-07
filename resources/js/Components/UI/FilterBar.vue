<template>
    <div>
        <div
            class="container grid grid-cols-1 gap-2 px-4 my-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-12"
        >
            <div class="col-span-1 lg:col-span-2">
                <brands v-model="selectedBrand" />
            </div>
            <div class="col-span-1 lg:col-span-2">
                <categories v-model="selectedCategory" />
            </div>
            <div class="col-span-1 lg:col-span-2">
                <colors v-model="selectedColor" />
            </div>
            <div class="col-span-1 lg:col-span-2">
                <sizes v-model="selectedSize" />
            </div>
            <div class="col-span-1 lg:col-span-2">
                <searchForm />
            </div>
            <div class="flex items-center justify-end col-span-1 lg:col-span-1">
                <button
                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                    @click="clearFilters"
                >
                    Clear all
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import brands from "@/Components/partials/brands.vue";
import categories from "../partials/categories.vue";
import colors from "../partials/colors.vue";
import sizes from "../partials/sizes.vue";
import searchForm from "../partials/searchForm.vue";
import { useProductStore } from "@/stores/productStore";

const productStore = useProductStore();

const selectedBrand = ref("");
const selectedCategory = ref("");
const selectedColor = ref("");
const selectedSize = ref("");

const clearFilters = () => {
    selectedBrand.value = "";
    selectedCategory.value = "";
    selectedColor.value = "";
    selectedSize.value = "";
    productStore.clearFilters();
};
</script>

<style scoped></style>
