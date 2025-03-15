<template>
    <Head title="Home" />

    <userLayout>
        <div
            v-if="productDetails.selectedProduct"
            class="container mx-auto mb-12"
        >
            <div>
                <productListItem :product="productDetails.selectedProduct" />
            </div>
        </div>
        <div v-else class="container mx-auto mb-12">
            <div class="flex items-center justify-center">
                <spinner :store="productStore" />
            </div>
            <FilterBar />
            <div v-if="productStore.filter" class="flex">
                <div class="mb-3">
                    <h1 class="text-2xl font-semibold text-gray-800">
                        Found {{ productStore.products.length }}
                        {{
                            productStore.products.length > 1
                                ? "products"
                                : "product"
                        }}
                    </h1>
                </div>
            </div>
            <div
                class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
            >
                <productList
                    v-for="product in slicedProducts"
                    :key="product.id"
                    class="h-full"
                    :product="product"
                    @selectProduct="selectProduct"
                ></productList>
            </div>
            <div class="flex justify-center">
                <button
                    type="submit"
                    class="mt-3 text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
                    v-if="productsToShow < productStore.products.length"
                    @click="loadMoreProducts"
                >
                    <ArrowDownIcon style="width: 16px; height: 16px" />
                </button>
            </div>
        </div>
    </userLayout>
</template>

<script setup>
import { onMounted, computed, ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import userLayout from "@/Layouts/userLayout.vue";
import { useProductStore } from "@/stores/productStore";
import spinner from "@/Layouts/spinner.vue";
import FilterBar from "@/Components/UI/FilterBar.vue";
import productList from "./products/productList.vue";
import { ArrowDownIcon } from "@heroicons/vue/24/outline";
import productListItem from "./products/productListItem.vue";
import { useProductDetailsStore } from "@/stores/productDetailsStore";
import Swal from "sweetalert2";

const productStore = useProductStore();
const productDetails = useProductDetailsStore();
const { flash } = usePage().props;

const selectProduct = (product) => {
    productDetails.setSelectedProduct(product);
};

const productsToShow = ref(5);

const slicedProducts = computed(() => {
    return productStore.products
        ? productStore.products.slice(0, productsToShow.value)
        : [];
});

onMounted(async () => {
    await productStore.fetchAllProducts();
    console.log("Home.vue-products", productStore.products);
    if (flash.success) {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: flash.success,
            showConfirmButton: false,
            timer: 2000,
        }).then(() => {
            flash.success = null;
        });
        return { productStore };
    }
});

const loadMoreProducts = () => {
    if (
        productsToShow.value <
        (productStore.products ? productStore.products.length : 0)
    ) {
        productsToShow.value += 6;
    } else {
        return;
    }
};
</script>

<style scoped></style>
