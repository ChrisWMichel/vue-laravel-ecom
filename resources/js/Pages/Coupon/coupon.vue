<template>
    <div>
        <div class="mb-3">
            <h1 class="text-2xl font-bold">Coupons</h1>
            <p class="mb-2">
                Add
                <span
                    class="p-1 font-bold text-gray-300 bg-green-600 rounded-md"
                    >LARAVEL</span
                >
                in the coupon field to get 50% off!
            </p>
            <div class="col-span-12">
                <div class="flex">
                    <input
                        id="coupon"
                        v-model="couponInput"
                        type="text"
                        placeholder="Enter coupon name..."
                        class="px-4 py-2 uppercase border rounded-l-md"
                        @input="handleCouponInput"
                    />
                    <button
                        class="px-4 py-2 text-white bg-gray-800 cursor-pointer rounded-r-md disabled:cursor-not-allowed disabled:opacity-50"
                        :disabled="!data.coupon.name"
                        @click="applyCoupon"
                    >
                        Apply
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { useCartStore } from "@/stores/useCartStore";
import { useToast } from "vue-toastification";
import { reactive, ref } from "vue";
import axios from "axios";
import { BASE_URL } from "@/helpers/config";

const cartStore = useCartStore();
const toast = useToast();
const user = usePage().props.auth.user;
const couponInput = ref("");

const data = reactive({
    coupon: {
        name: "",
    },
});

const handleCouponInput = (event) => {
    // Convert to uppercase
    couponInput.value = event.target.value.toUpperCase();
    data.coupon.name = couponInput.value;
};

const applyCoupon = async () => {
    try {
        const response = await axios.post(
            `${BASE_URL}/apply/coupon`,
            data.coupon,
            {
                headers: {
                    Authorization: `Bearer ${user?.api_token}`,
                },
            }
        );
        if (response.data.error) {
            toast.error(response.data.error, {
                timeout: 2000,
            });
            data.coupon = {
                name: "",
            };
        } else {
            cartStore.setValidCoupon(response.data.coupon);
            cartStore.addCouponToCartItems(response.data.coupon.id);
            toast.success(response.data.message);
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            toast.error(error.response.data.message, {
                timeout: 2000,
            });
        } else {
            toast.error("An unexpected error occurred", {
                timeout: 2000,
            });
        }
        data.coupon.name = "";
    }
};
</script>

<style scoped>
input.uppercase {
    text-transform: uppercase;
}
</style>
