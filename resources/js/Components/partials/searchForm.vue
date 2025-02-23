<template>
    <div class="flex justify-between mb-3 ml-5">
        <input
            type="text"
            v-model="data.term"
            class="px-4 py-2 leading-tight text-gray-700 rounded-l-full w-96 focus:outline-none"
            placeholder="Search..."
        />
        <button
            class="text-white bg-gray-500"
            @click="searchForProducts"
            :disabled="!data.term"
        >
            <MagnifyingGlassIcon class="w-10 h-10" />
        </button>
    </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useProductStore } from "@/stores/productStore";
import { MagnifyingGlassIcon } from "@heroicons/vue/24/outline";

const productsStore = useProductStore();

const data = reactive({
    term: "",
});

const searchForProducts = () => {
    productsStore.searchProducts(data.term);
    data.term = "";
};
</script>

<style scoped>
.search__button {
    border-top-right-radius: 15px;
    border-bottom-right-radius: 15px;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
</style>
