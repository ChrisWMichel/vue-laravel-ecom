<template>
    <div>
        <select
            id="brand-select"
            v-model="selectedBrand"
            @change="onBrandChange"
            class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        >
            <option value="" disabled selected>Select a brand</option>
            <option
                v-for="brand in productStore.brands"
                :key="brand.id"
                :value="brand"
            >
                {{ brand.name }}
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

const selectedBrand = ref("");

watch(
    () => props.modelValue,
    (newValue) => {
        selectedBrand.value = newValue;
    }
);

watch(selectedBrand, (newValue) => {
    emit("update:modelValue", newValue);
});

const onBrandChange = () => {
    productStore.filterProducts("brand", selectedBrand.value.slug);
};
</script>

<style scoped></style>
