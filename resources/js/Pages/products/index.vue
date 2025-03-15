<template>
    <Head title="Home" />

    <userLayout>
        <div class="container mx-auto mb-12">
            <div class="flex items-center justify-center">
                <spinner :store="productStore" />
            </div>
            <FilterBar />

            <div
                class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
            >
                <productList
                    v-for="product in productStore.products"
                    :key="product.id"
                    class="h-full"
                    :product="product"
                ></productList>
            </div>
            <div class="d-flex justify-content-center">
                <button
                    type="submit"
                    class="mt-3 btn btn-sm btn-dark"
                    v-if="data.productsToShow < productStore.products.length"
                    @click="loadMoreProducts"
                >
                    <i class="bi bi-arrow-clockwise"></i> Load more
                </button>
            </div>
        </div>
    </userLayout>
</template>

<script setup>
import { onMounted } from "vue";
import { Head } from "@inertiajs/vue3";
import userLayout from "@/Layouts/userLayout.vue";
import productList from "./productList.vue";
import { useProductStore } from "@/stores/productStore";
import spinner from "@/Layouts/spinner.vue";
import FilterBar from "@/Components/UI/FilterBar.vue";

const productStore = useProductStore();
//define how many products to show on the home page
const data = reactive({
    productsToShow: 10,
});

onMounted(() => {
    productStore.fetchAllProducts();

    console.log("productStore.products", productStore);
});

const loadMoreProducts = () => {
    if (data.productsToShow < productStore.products.length) {
        data.productsToShow = data.productsToShow + 6;
    } else {
        return;
    }
};
</script>

<style scoped></style>
