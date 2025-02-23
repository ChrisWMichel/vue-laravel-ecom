import { defineStore } from "pinia";
import { ref } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";

export const useProductDetailsStore = defineStore("productDetails", () => {
    const productThumbnail = ref(null);
    const productImages = ref([]);
    const isLoading = ref(false);
    const toast = useToast();
    const user = usePage().props.auth.user;
    const reviewToUpdate = ref({
        updating: false,
        data: null,
    });
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

    const editReview = (review) => {
        reviewToUpdate.value = {
            updating: true,
            data: review,
        };
    };
    const cancelUpdateReview = () => {
        reviewToUpdate.value = {
            updating: false,
            data: null,
        };
    };
    const storeReview = (review) => {
        isLoading.value = true;

        const form = useForm({
            title: review.title,
            body: review.body,
            rating: review.rating,
            product_id: selectedProduct.value.id,
            user_id: user.id,
        });
        form.post("store/review", {
            onSuccess: (page) => {
                if (page.props.flash.success) {
                    toast.success(page.props.flash.success);
                } else if (page.props.flash.error) {
                    toast.error(page.props.flash.error);
                }
            },
            onError: (errors) => {
                toast.error("An error occurred. Try again later.");
                console.log(errors);
            },
            onFinish: () => {
                isLoading.value = false;
                reviewToUpdate.value = {
                    updating: false,
                    data: null,
                };
            },
        });
    };

    const updateReview = (review) => {
        isLoading.value = true;

        const form = useForm({
            title: review.title,
            body: review.body,
            rating: review.rating,
            product_id: selectedProduct.value.id,
            user_id: user.id,
        });
        form.put(`update/review/${review.id}`, {
            onSuccess: (page) => {
                if (page.props.flash.success) {
                    toast.success(page.props.flash.success);
                    selectedProduct.value.reviews =
                        selectedProduct.value.reviews.map((r) => {
                            if (r.id === review.id) {
                                r.title = review.title;
                                r.body = review.body;
                                r.rating = review.rating;
                            }
                            return r;
                        });
                } else if (page.props.flash.error) {
                    toast.error(page.props.flash.error);
                }
            },
            onError: (errors) => {
                toast.error("An error occurred. Try again later.");
                console.log(errors);
            },
            onFinish: () => {
                isLoading.value = false;
                reviewToUpdate.value = {
                    updating: false,
                    data: null,
                };
            },
        });
    };

    const fetchReviews = async (productId) => {
        isLoading.value = true;
        try {
            const response = await axios.get(`/reviews/${productId}`);
            if (response.data.reviews) {
                selectedProduct.value.reviews = response.data.reviews;
            } else {
                toast.error("Failed to fetch reviews.");
            }
        } catch (error) {
            toast.error("Failed to fetch reviews.");
            console.error(error);
        } finally {
            isLoading.value = false;
        }
    };

    const removeReview = (review_id) => {
        const form = useForm({
            review_id: review_id,
            user_id: user.id,
        });

        form.delete(`delete/review/${review_id}`, {
            onSuccess: (page) => {
                if (page.props.flash.success) {
                    toast.success(page.props.flash.success);
                    selectedProduct.value.reviews =
                        selectedProduct.value.reviews.filter(
                            (r) => r.id !== review_id
                        );
                } else if (page.props.flash.error) {
                    toast.error(page.props.flash.error);
                }
            },
            onError: (errors) => {
                toast.error("An error occurred. Try again later.");
                console.log(errors);
            },
        });
    };

    return {
        productThumbnail,
        productImages,
        isLoading,
        selectedProduct,
        setSelectedProduct,
        unsetSelectedProduct,
        editReview,
        cancelUpdateReview,
        storeReview,
        removeReview,
        fetchReviews,
        updateReview,
    };
});
