<template>
    <div class="flex flex-col h-full bg-white rounded-lg shadow">
        <div class="cursor-pointer" @click="selectProduct">
            <div class="text-center">
                <img
                    :src="product.thumbnail"
                    :alt="product.name"
                    class="mx-auto mb-4 w-42 h-42"
                />
            </div>
            <!-- <div class="mt-4"> -->
            <div>
                <h2 class="mb-4 ml-4 text-lg font-semibold text-gray-800">
                    {{ product.name }}
                </h2>
                <p
                    class="mx-4 mb-4 text-sm text-gray-600"
                    v-dompurify-html="product.desc.substring(0, 75) + '...'"
                ></p>

                <div class="flex items-center justify-between mx-4">
                    <FormatPrice :price="product.price" />
                    <div class="flex flex-col items-center col-span-1">
                        <star-rating
                            :rating="stars"
                            :value="stars"
                            :max-stars="5"
                            :font-size="26"
                            :width="'w-4'"
                        />
                        <span class="text-sm text-gray-600 star-spacing">
                            {{ numberOfReviews }}
                            {{ numberOfReviews > 1 ? "reviews" : "review" }}
                        </span>
                    </div>
                </div>
            </div>
            <div v-if="!product.status">
                <span class="p-2 ml-4 bg-yellow-500 rounded-md">
                    Out of Stock
                </span>
            </div>
        </div>
        <div class="flex items-center justify-between p-4 mt-auto bg-gray-200">
            <button
                :class="{
                    'cursor-not-allowed': !product.status,
                    'bg-blue-500': product.status,
                    'bg-gray-500': !product.status,
                }"
                :disabled="!product.status"
                class="flex items-center justify-between px-4 py-2 text-white bg-blue-500 rounded-lg"
                @click="selectProduct"
            >
                <ShoppingCartIcon class="w-6 h-6 mr-2" />
                Add to Cart
            </button>
            <svg
                @click="toggleFavorite(product)"
                xmlns="http://www.w3.org/2000/svg"
                :fill="
                    favoriteStore.isFavorite(product).value
                        ? 'currentColor'
                        : 'none'
                "
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="w-6 h-6 text-red-500 cursor-pointer"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3.172 5.172a4.001 4.001 0 015.656 0L12 8.343l3.172-3.171a4.001 4.001 0 115.656 5.656L12 18.343l-8.828-8.829a4.001 4.001 0 010-5.656z"
                />
            </svg>
        </div>
        <!-- </div> -->
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import FormatPrice from "@/Components/UI/FormatPrice.vue";
import productReview from "@/Components/UI/productReview.vue";
import { ShoppingCartIcon } from "@heroicons/vue/24/outline";
import starRating from "../reviews/starRating.vue";
import { useFavoriteStore } from "@/stores/useFavoriteStore";

const props = defineProps({
    product: Object,
});
const favoriteStore = useFavoriteStore();

const emit = defineEmits(["selectProduct"]);
const numberOfReviews = ref(0);
const selectProduct = () => {
    emit("selectProduct", props.product);
};

const toggleFavorite = (product) => {
    if (favoriteStore.isFavorite(product).value) {
        favoriteStore.removeFromFavorite(product);
    } else {
        favoriteStore.addToFavorite(product);
    }
};

// calculate the average of reviews
const averageRating = (starNum) => {
    if (!Array.isArray(starNum) || starNum.length === 0) {
        numberOfReviews.value = 0; // Reset the number of reviews if no data
        return 0;
    }
    numberOfReviews.value = starNum.length;
    const total = starNum.reduce(
        (acc, review) => acc + (review.rating || 0),
        0
    ); // Ensure 'rating' exists
    return total / starNum.length;
};

const stars = computed(() => {
    return props.product && props.product.reviews
        ? averageRating(props.product.reviews)
        : 0;
});
</script>

<style scoped>
.star-spacing {
    margin-top: -10px;
}
</style>
