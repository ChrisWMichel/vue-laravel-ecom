<template>
    <div class="mt-5">
        <div class="mb-10 text-2xl text-center">
            Reviews: {{ productDetails.selectedProduct.reviews.length }}
        </div>
        <div
            class="grid gap-2 lg:gap-4 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1"
        >
            <div
                v-for="review in productDetails.selectedProduct.reviews"
                :key="review.id"
                class="w-full p-5 mx-auto rounded-lg bg-slate-400"
            >
                <div>
                    <div class="grid grid-cols-[3fr_1fr_1fr] mb-1">
                        <div class="mt-2">
                            {{ review.created_at }} by {{ review.user.name }}
                        </div>
                        <img
                            v-if="review.user.profile_image"
                            :src="review.user.profile_image"
                            class="inline-block w-8 h-8 rounded-full"
                        />
                        <div
                            class="mr-10"
                            :class="{
                                'col-span-2 justify-self-end':
                                    !review.user.profile_image,
                                'col-span-1': review.user.profile_image,
                            }"
                        >
                            <display-star-rating :rating="review.rating" />
                        </div>
                    </div>
                    <div
                        class="p-3 text-lg font-extrabold border-b-2 border-slate-900 rounded-t-md bg-slate-200"
                    >
                        {{ review.title }}
                    </div>
                    <div
                        class="p-3 border border-slate-900 bg-slate-200 rounded-b-md"
                    >
                        {{ review.body }}
                    </div>
                    <div
                        v-if="user && review.user_id === user.id"
                        class="flex justify-between"
                    >
                        <button
                            type="button"
                            @click="productDetails.removeReview(review.id)"
                            class="px-2 py-2 ml-5 text-red-700"
                        >
                            <span class="flex items-center">
                                <TrashIcon class="w-5 h-5" />
                            </span>
                        </button>
                        <button class="px-2 py-2 mr-5 text-green-700">
                            <span
                                type="button"
                                @click="
                                    () => {
                                        selectedReview.value = review;
                                        showModal = true;
                                    }
                                "
                                class="flex items-center"
                            >
                                <PencilIcon class="w-5 h-5" />
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div v-if="productDetails.selectedProduct.reviews.length === 0">
                <div class="text-2xl text-center">No reviews yet</div>
            </div>
        </div>
    </div>
    <div></div>
    <Modal
        :show="showModal"
        @close="showModal = false"
        @fetchReviews="fetchReviews"
    >
        <updateReview
            :review="selectedReview.value"
            @fetchReviews="fetchReviews"
            @closeModal="showModal = false"
        />
    </Modal>
</template>

<script setup>
import { useProductDetailsStore } from "@/stores/productDetailsStore";
import displayStarRating from "./displayStarRating.vue";
import { usePage } from "@inertiajs/vue3";
import { PencilIcon, TrashIcon } from "@heroicons/vue/24/outline";
import { onMounted, ref } from "vue";
import Modal from "@/Components/Modal.vue";
import updateReview from "./updateReview.vue";
import { useToast } from "vue-toastification";

const toast = useToast();
const user = usePage().props.auth.user || null;

const productDetails = useProductDetailsStore();
const showModal = ref(false);
const selectedReview = ref({});

onMounted(() => {
    if (productDetails.selectedProduct) {
        productDetails.fetchReviews(productDetails.selectedProduct.id);
    }
});

const fetchReviews = () => {
    productDetails.fetchReviews(productDetails.selectedProduct.id);
    toast.success("Review updated successfully");
};
</script>

<style scoped></style>
