<template>
    <div>
        <select
            id="color-select"
            v-model="selectedColor"
            @change="onColorChange"
            class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm hover:bg-transparent"
        >
            <option value="" disabled selected>Select a color</option>
            <option
                v-for="color in productStore.colors"
                :key="color.id"
                :value="color"
                :style="{ backgroundColor: color.name }"
            >
                {{ color.name }}
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

const selectedColor = ref("");

watch(
    () => props.modelValue,
    (newValue) => {
        selectedColor.value = newValue;
    }
);

watch(selectedColor, (newValue) => {
    emit("update:modelValue", newValue);
});

const onColorChange = () => {
    productStore.filterProducts("color", selectedColor.value.id);
};
</script>

<style scoped></style>
