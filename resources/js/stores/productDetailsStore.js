import { defineStore } from "pinia";
import { ref } from "vue";
//import axios from "axios";
//import { BASE_URL } from "../helpers/config";

export const useProductDetailsStore = defineStore("productDetails", () => {
    const productThumbnail = ref(null);
    const productImages = ref([]);
    const isLoading = ref(false);
    const selectedProduct =
        ref(JSON.parse(localStorage.getItem("selectedProduct"))) || ref(null);

    const setSelectedProduct = (product) => {
        selectedProduct.value = { ...product, productImages: [] };
        getImages(product);
        selectedProduct.value.productImages = productImages.value;

        localStorage.setItem(
            "selectedProduct",
            JSON.stringify(selectedProduct.value)
        );
    };

    const unsetSelectedProduct = () => {
        selectedProduct.value = null;
        localStorage.removeItem("selectedProduct");
        productImages.value = [];
    };

    const getImages = (product) => {
        if (product.first_image) {
            productImages.value.push(product.first_image);
        }
        if (product.second_image) {
            productImages.value.push(product.second_image);
        }
        if (product.third_image) {
            productImages.value.push(product.third_image);
        }
        productImages.value.push(product.thumbnail);
    };
    //     isLoading.value = true;
    //     try {
    //         const response = await axios.get(`${BASE_URL}/products`);
    //         products.value = response.data.data;
    //         categories.value = response.data.categories;
    //         colors.value = response.data.colors;
    //         brands.value = response.data.brands;
    //         sizes.value = response.data.sizes;
    //     } catch (error) {
    //         console.log(error);
    //     } finally {
    //         isLoading.value = false;
    //     }
    // };

    return {
        productThumbnail,
        productImages,
        isLoading,
        selectedProduct,
        setSelectedProduct,
        unsetSelectedProduct,
    };
});
