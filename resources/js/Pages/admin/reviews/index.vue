<template>
    <Head title="Reviews" />
    <adminLayout>
        <div class="mt-5">
            <div class="col-span-12 p-6 text-gray-900">
                <div class="flex items-center justify-between">
                    <h1 class="mb-2 text-2xl text-left text-black">
                        Unapproved Reviews: ({{ totalCount }})
                    </h1>
                </div>
                <hr class="my-2 border-t-2 border-indigo-700" />
            </div>
            <div
                class="grid gap-2 lg:gap-4 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1"
            >
                <div
                    v-for="review in allReviews"
                    :key="review.id"
                    class="w-full p-5 mx-auto rounded-lg bg-slate-400"
                >
                    <div>
                        <div class="grid grid-cols-[2fr_1fr_1fr] mb-1">
                            <div>
                                {{ review.created_at }} by
                                {{ review.user.name }}
                            </div>

                            <div class="justify-self-center">
                                {{ review.user.email }}
                            </div>
                            <div class="justify-self-end">
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
                        <div class="flex justify-between">
                            <button
                                type="button"
                                @click="deleteReview(review)"
                                class="px-2 py-2 ml-5 text-red-700"
                            >
                                <span class="flex items-center">
                                    <TrashIcon class="w-5 h-5" />
                                </span>
                            </button>
                            <div class="mt-10 font-semibold">
                                {{ review.product.name }}
                            </div>
                            <button
                                type="button"
                                @click="approveReview(review, 1)"
                                class="px-2 py-2 mt-3 mr-5 text-gray-300 bg-green-700 rounded-md"
                            >
                                Approve
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </adminLayout>
</template>

<script setup>
import Swal from "sweetalert2";
import displayStarRating from "@/Pages/reviews/displayStarRating.vue";
import { usePage, Head, router } from "@inertiajs/vue3";
import { TrashIcon } from "@heroicons/vue/24/outline";
import { onMounted, ref, toRefs } from "vue";
import axios from "axios";
import adminLayout from "@/Layouts/adminLayout.vue";

const props = defineProps({
    reviews: Array,
    flash: Object,
    totalCount: Number,
});

const allReviews = ref([...props.reviews]);
const flash = ref(props.flash);
const totalCount = ref(props.totalCount);

const deleteReview = async (review) => {
    Swal.fire({
        title: "Are you sure you want to delete this review?",
        showCancelButton: true,
        confirmButtonText: "Delete",
        denyButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("delete.review", review), {
                onFinish: () => {
                    Swal.fire("Review deleted successfully", "", "success");
                    allReviews.value = props.reviews;
                    totalCount.value = props.reviews.length;
                },
            });
        } else if (result.isDismissed) {
            Swal.fire("Changes are not saved", "", "info");
        }
    });
};

const approveReview = async (review, status) => {
    try {
        const response = await axios.post(
            `/admin/reviews/${review.id}/toggle/${status}`
        );

        allReviews.value = response.data.reviews;
        flash.value.success = response.data.success;
        totalCount.value = response.data.reviews.length;

        Swal.fire({
            position: "top-end",
            icon: "success",
            title: flash.value.success,
            showConfirmButton: false,
            timer: 2000,
        });
    } catch (error) {
        console.error("Error updating review status:", error);
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: "Failed to update review status",
            showConfirmButton: false,
            timer: 2000,
        });
    }
};
</script>

<style scoped></style>
