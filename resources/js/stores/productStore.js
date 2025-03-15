import { defineStore } from "pinia";
import { ref } from "vue";
import axios from "axios";
import { BASE_URL } from "../helpers/config";

export const useProductStore = defineStore("products", () => {
    // State
    const products = ref([]);
    const categories = ref([]);
    const colors = ref([]);
    const brands = ref([]);
    const sizes = ref([]);
    const isLoading = ref(false);
    const filter = ref(null);

    const fetchAllProducts = async () => {
        isLoading.value = true;
        try {
            const response = await axios.get(`${BASE_URL}/products`);
            products.value = response.data.data;
            categories.value = response.data.categories;
            colors.value = response.data.colors;
            brands.value = response.data.brands;
            sizes.value = response.data.sizes;
            console.log("products-Pinia", products.value);
        } catch (error) {
            console.log(error);
        } finally {
            isLoading.value = false;
        }
    };

    const filterProducts = async (param, value) => {
        isLoading.value = true;
        try {
            const response = await axios.get(
                `${BASE_URL}/products/${value}/${param}`
            );
            products.value = response.data.data;
            categories.value = response.data.categories;
            colors.value = response.data.colors;
            brands.value = response.data.brands;
            sizes.value = response.data.sizes;
            filter.value = { param, value: response.data.filter };
        } catch (error) {
            console.log("Error in API call", error);
        } finally {
            isLoading.value = false;
        }
    };

    const searchProducts = async (term) => {
        isLoading.value = true;

        try {
            const response = await axios.get(
                `${BASE_URL}/products/search/${term}`
            );

            products.value = response.data.data;
            categories.value = response.data.categories;
            colors.value = response.data.colors;
            brands.value = response.data.brands;
            sizes.value = response.data.sizes;
            filter.value = { param: "term", value: term };
        } catch (error) {
            console.log("Error in API call", error);
        } finally {
            isLoading.value = false;
        }
    };

    const clearFilters = () => {
        filter.value = null;
        fetchAllProducts();
    };

    return {
        products,
        categories,
        colors,
        brands,
        sizes,
        isLoading,
        filter,
        fetchAllProducts,
        filterProducts,
        clearFilters,
        searchProducts,
    };
});
