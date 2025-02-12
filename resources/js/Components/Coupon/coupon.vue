<template>
    <div>
        <div class="mb-3">
            <h1 class="text-2xl font-bold">Coupons</h1>
            <p class="mb-2">
                Add
                <span class="bg-green-600 font-bold text-gray-300 p-1"
                    >Vuejs</span
                >
                in the coupon field to get 20% off!
            </p>
            <div class="col-span-12">
                <div class="flex">
                    <input
                        v-model="data.coupon.name"
                        type="text"
                        placeholder="Enter coupon name..."
                        class="px-4 py-2 border rounded-l-md"
                    />
                    <button
                        class="px-4 py-2 text-white bg-gray-800 rounded-r-md cursor-pointer disabled:cursor-not-allowed disabled:opacity-50"
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
import { reactive } from "vue";
import axios from "axios";
import { BASE_URL } from "@/helpers/config";

const cartStore = useCartStore();
const toast = useToast();
const user = usePage().props.auth.user;

const data = reactive({
    coupon: {
        name: "",
    },
});

const applyCoupon = async () => {
    try {
        const response = await axios.post(
            `${BASE_URL}/apply/coupon`,
            data.coupon,
            {
                headers: {
                    Authorization: `Bearer ${user.api_token}`,
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

<style scoped></style>
