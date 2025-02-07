<template>
    <div>
        <select
            id="category-select"
            v-model="selectedCategory"
            @change="onCategoryChange"
            class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        >
            <option value="" disabled selected>Select a category</option>
            <option
                v-for="category in productStore.categories"
                :key="category.id"
                :value="category"
            >
                {{ category.name }}
            </option>
        </select>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { useProductStore } from "@/stores/productStore";

const productStore = useProductStore();

const props = defineProps({
    modelValue: {
        type: [Object, String, null],
        default: null,
    },
});

const emit = defineEmits(["update:modelValue"]);

const selectedCategory = ref("");

watch(
    () => props.modelValue,
    (newValue) => {
        selectedCategory.value = newValue;
    }
);

watch(selectedCategory, (newValue) => {
    emit("update:modelValue", newValue);
});

const onCategoryChange = () => {
    productStore.filterProducts("category", selectedCategory.value.slug);
};
</script>

<style scoped></style>
