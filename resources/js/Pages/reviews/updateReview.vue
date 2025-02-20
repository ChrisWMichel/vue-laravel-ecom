<template>
    <div
        class="flex flex-col items-center justify-center w-full h-full bg-slate-200 bg-surface"
    >
        <div
            class="flex items-center justify-center w-full mt-10 text-2xl font-extrabold"
        >
            Update your review
        </div>
        <div class="pt-4">
            <form class="flex-col mx-auto" @submit.prevent="updateReview">
                <input
                    v-model="data.review.title"
                    label="Title"
                    placeholder="Title"
                    id="title"
                    :required="true"
                    class="form-input"
                />
                <textarea
                    v-model="data.review.body"
                    label="Review"
                    class="form-textarea"
                    :required="true"
                    placeholder="Review"
                ></textarea>
                <div class="w-full">
                    <div class="flex justify-center text-red-500">
                        {{ data.errors.rating }}
                    </div>
                    <div class="flex justify-center bg-slate-100 rounded-xl">
                        <star-rating
                            :rating="stars"
                            v-model="data.review.rating"
                            :max-stars="5"
                            @ratingData="updateRating"
                        />
                    </div>
                </div>
                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="mt-3 standard-button"
                        width="100%"
                    >
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useProductDetailsStore } from "@/stores/productDetailsStore";
import starRating from "./starRating.vue";
import { ref, watch, computed } from "vue";

const productDetails = useProductDetailsStore();

const props = defineProps({
    review: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["closeModal", "fetchReviews"]);

const data = ref({
    review: {
        title: props.review.title,
        rating: props.review.rating,
        body: props.review.body,
    },
    errors: {},
});

const stars = computed({
    get() {
        return data.value.review.rating;
    },
    set(value) {
        data.value.review.rating = value;
    },
});

watch(
    () => props.review,
    (newReview) => {
        data.value.review.title = newReview.title;
        data.value.review.rating = newReview.rating;
        data.value.review.body = newReview.body;
    },
    { immediate: true }
);

const updateRating = (rating) => {
    data.value.review.rating = rating;
};

const updateReview = () => {
    productDetails.updateReview(data.value.review);
    data.value.review = {
        title: "",
        rating: 0,
        body: "",
    };
    emit("fetchReviews");
    emit("closeModal");
};
</script>

<style scoped>
.form-input,
.form-textarea {
    width: 100%;
    margin-bottom: 1rem;
}
</style>
