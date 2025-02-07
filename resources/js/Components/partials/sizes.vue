<template>
    <div>
        <select
            id="size-select"
            v-model="selectedSize"
            @change="onSizeChange"
            class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        >
            <option value="" disabled selected>Select a size</option>
            <option
                v-for="size in productStore.sizes"
                :key="size.id"
                :value="size"
            >
                {{ size.name }}
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

const selectedSize = ref("");

watch(
    () => props.modelValue,
    (newValue) => {
        selectedSize.value = newValue;
    }
);

watch(selectedSize, (newValue) => {
    emit("update:modelValue", newValue);
});

const onSizeChange = () => {
    productStore.filterProducts("size", selectedSize.value.id);
};
</script>

<style scoped></style>
