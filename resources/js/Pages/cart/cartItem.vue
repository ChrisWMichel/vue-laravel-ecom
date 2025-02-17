<template>
    <td class="p-2 text-center border">
        <div class="flex items-center justify-center">
            <img
                class="w-20 h-20 mb-1 rounded-lg"
                :src="getImageUrl(product.thumbnail)"
                :alt="product.name"
            />
        </div>
    </td>
    <td class="p-2 text-center border">
        {{ product.name }}
    </td>

    <td class="p-2 text-center border">
        <FormatPrice :price="product.price" />
    </td>

    <td class="p-2 text-center border">
        <div class="flex flex-col items-center">
            <span
                class="px-2 py-1 mb-1 text-white rounded-md"
                :style="{ backgroundColor: product.color }"
            >
                {{ product.color }}
            </span>
        </div>
    </td>
    <td class="p-2 text-center border">
        <div class="flex flex-col items-center">
            <span class="px-2 py-1 mb-1 text-white bg-gray-600 rounded-md">
                {{ product.size }}
            </span>
        </div>
    </td>
    <td class="p-2 text-center border">
        <div class="flex flex-col items-center">
            <span class="px-2 py-1 mb-1">
                <FormatPrice :price="product.price * quantity" />
            </span>
        </div>
    </td>
    <td class="p-2 text-center border">
        <input
            type="number"
            class="w-16 p-2 text-center border"
            v-model="quantity"
        />
    </td>

    <td class="p-2 text-center border">
        <button
            class="px-2 py-2 text-red-500 rounded-md"
            @click="emitRemoveItem"
        >
            <span class="flex items-center"> X </span>
        </button>
    </td>
</template>

<script setup>
import { defineProps, ref, watch } from "vue";
import FormatPrice from "@/Components/UI/FormatPrice.vue";
import { useCartStore } from "@/stores/useCartStore";

const cartStore = useCartStore();

const props = defineProps({
    product: Object,
});

const emit = defineEmits(["removeItem"]);

const emitRemoveItem = () => {
    emit("removeItem", props.product);
};

const quantity = ref(props.product.qty);

const changeQuantity = (product) => {
    cartStore.changeQty(product, quantity.value);
};

watch(quantity, () => {
    changeQuantity(props.product);
});

const getImageUrl = (path) => {
    return path ? `${window.location.origin}/${path}` : "";
};
</script>

<style scoped>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* input[type="number"] {
    -moz-appearance: textfield;
} */
</style>
