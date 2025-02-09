<template>
    <div class="container mx-5 mb-12">
        <button @click="goBack" class="standard-button">Back to Home</button>
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-12">
            <div class="col-span-1 lg:col-span-4">
                <img :src="thumbnail" class="w-full" :alt="product.name" />

                <div class="flex gap-2 mt-4 overflow-x-auto whitespace-nowrap">
                    <div
                        v-for="image in productImages"
                        :key="image"
                        class="inline-block"
                    >
                        <img
                            :src="image"
                            class="w-32 cursor-pointer"
                            :alt="product.name"
                            :class="{
                                'border-4 border-blue-500':
                                    image === selectedImage,
                            }"
                            @click="updateThumbnail(image)"
                        />
                    </div>
                </div>
            </div>
            <div class="col-span-1 lg:col-span-6 product-details">
                <h1 class="text-2xl font-bold">{{ product.name }}</h1>
                <p v-dompurify-html="product.desc" class="my-4 text-lg"></p>
                <FormatPrice
                    :price="product.price"
                    class="text-2xl font-extrabold"
                />
                <div class="mt-4 text-lg">Select Color</div>
                <div class="flex flex-wrap justify-start">
                    <div
                        v-for="color in productDetails.selectedProduct.colors"
                        :key="color.id"
                        :style="{
                            backgroundColor: color.name,
                            width: '30px',
                            height: '30px',
                            cursor: 'pointer',
                        }"
                        class="mb-2 mr-2 border-2 border-gray-500 rounded-full"
                        :class="{
                            'color-border': color.name === selectedColor,
                        }"
                        @click="selectColor(color.name)"
                    ></div>
                </div>
                <div class="mt-4 text-lg">Select Size</div>
                <div class="flex flex-wrap justify-start">
                    <div
                        v-for="size in productDetails.selectedProduct.sizes"
                        :key="size.id"
                        :style="{
                            width: '80px',
                            height: '30px',
                            cursor: 'pointer',
                        }"
                        class="flex items-center justify-center mb-2 mr-2 text-black border-2 border-gray-500"
                        :class="{
                            'size-bg': size.name === selectedSize,
                        }"
                        @click="selectSize(size.name)"
                    >
                        {{ size.name }}
                    </div>
                </div>
                <div class="mt-4">
                    <span
                        class="p-2 bg-green-500 rounded-md"
                        v-if="productDetails.selectedProduct?.status"
                    >
                        In Stock
                        <span class="text-xs"
                            >({{ productDetails.selectedProduct?.qty }})</span
                        >
                    </span>
                    <span class="p-2 bg-red-500 rounded-md" v-else>
                        Out of Stock
                    </span>
                </div>
                <div class="inline-flex items-center mt-4">
                    <div>
                        <div class="text-lg">Quantity</div>
                        <input
                            type="number"
                            v-model="quantity"
                            min="1"
                            :max="productDetails.selectedProduct?.qty"
                            class="form-control"
                        />
                    </div>
                    <div class="ml-2 mt-9">
                        <button
                            :class="{
                                disabled: isAddToCartDisabled,
                            }"
                            :disabled="isAddToCartDisabled"
                            class="!rounded-md standard-button"
                            @click="addToCart"
                        >
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import { useProductDetailsStore } from "@/stores/productDetailsStore";
import FormatPrice from "@/Components/UI/FormatPrice.vue";
import { useCartStore } from "@/stores/useCartStore";
import { makeUniqueId } from "@/helpers/config";

const props = defineProps({
    product: Object,
});

const cart = useCartStore();
const productDetails = useProductDetailsStore();
const productImages = ref([]);
const thumbnail = ref(props.product.thumbnail);
const selectedImage = ref(thumbnail.value);
const selectedColor = ref("");
const selectedSize = ref("");
const quantity = ref(1);
const attributes = ref({});
//console.log("productDetails", productDetails.selectedProduct);
onMounted(() => {
    if (productDetails.selectedProduct) {
        productImages.value =
            productDetails.selectedProduct.productImages || [];
    }
});

const selectColor = (colorName) => {
    selectedColor.value = colorName;
};
const selectSize = (sizeName) => {
    selectedSize.value = sizeName;
};

const updateThumbnail = (image) => {
    thumbnail.value = image;
    selectedImage.value = image;
};

const isAddToCartDisabled = computed(() => {
    return (
        !selectedColor.value ||
        !selectedSize.value ||
        !quantity.value ||
        !productDetails.selectedProduct?.status
    );
});

const addToCart = () => {
    if (isAddToCartDisabled.value) {
        return;
    }
    attributes.value = {
        color: selectedColor.value,
        size: selectedSize.value,
        qty: quantity.value,
        product_id: productDetails.selectedProduct.id,
        price: productDetails.selectedProduct.price,
        name: productDetails.selectedProduct.name,
        thumbnail: thumbnail.value,
        desc: productDetails.selectedProduct.desc,
        slug: productDetails.selectedProduct.slug,
        inStock: productDetails.selectedProduct.qty,
        coupon_id: null,
        ref: makeUniqueId(10),
    };
    const result = cart.addToCart(attributes.value);
    if (result.success) {
        productDetails.unsetSelectedProduct();
        router.get(route("home"));
    } else {
        console.error(result.message);
    }
};

const goBack = () => {
    productDetails.unsetSelectedProduct();
    router.get(route("home"));
};

//console.log("product", props.product);
</script>

<style scoped>
.flex-shrink-0 {
    flex-shrink: 0;
}

.color-border {
    border: 4px solid rgb(68, 93, 236) !important;
    width: 35px !important;
    height: 35px !important;
}
.size-bg {
    @apply bg-gray-600 text-white;
}
.disabled {
    @apply cursor-not-allowed hover:bg-red-500;
}
</style>
