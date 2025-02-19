<template>
    <div class="mt-10">
        <spinner :store="productDetails" />
            <div class="flex flex-col w-3/4 p-5 mx-auto rounded-md shadow-lg shadow-gray-900 bg-slate-500">
                <div class="flex items-center justify-center w-full text-2xl font-extrabold " >Add a review</div>
                
                <div class="pt-4 bg-surface-light">
                    <form class="flex-col mx-auto">
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
                        <div class="flex items-center justify-center w-full">
                            <div>
                                <star-rating
                                    v-model="data.review.rating"
                                    :max-stars="5"
                                    @ratingData="updateRating"
                                />
                            </div>
                        </div>
                       <div class="flex justify-end">
                        <button
                            @click="addReview"
                            type="button"
                            class="mt-3 standard-button"
                            width="100%"
                        >
                            Submit
                        </button>
                       </div>
                    </form>
                </div>
            </div>
                
                
            
    </div>
</template>
<script setup>
import { useProductDetailsStore } from "@/stores/productDetailsStore";
import spinner from "@/Layouts/spinner.vue";
import starRating from "./starRating.vue";
import { ref } from "vue";

const productDetails = useProductDetailsStore();

const data = ref({
    review: {
        title: "",
        rating: 0,
        body: "",
    },
    errors: {},
});

const updateRating = (rating) => {
    data.value.review.rating = rating;
};

const addReview =  () => {
    productDetails.storeReview(data.value.review)
    data.value.review = {
                title: "",
                rating: 0,
                body: "",
            };
    
        
};
</script>
<style scoped>


.form-input,
.form-textarea {
    width: 100%;
    margin-bottom: 1rem;
}
</style>
