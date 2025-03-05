<template>
    <div class="w-full mt-10">
        <button
            @click="fetchPaymentLink"
            type="submit"
            class="w-3/4 standard-button"
        >
            Proceed to Payment
        </button>
    </div>
</template>

<script setup>
import { useCartStore } from "@/stores/useCartStore";
import { makeUniqueId, BASE_URL } from "@/helpers/config";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";

const cartStore = useCartStore();
const hash = makeUniqueId(24);
const user = usePage().props.auth.user;

const fetchPaymentLink = async () => {
    try {
        const response = await axios.post(
            `${BASE_URL}/pay/order`,
            {
                cartItems: cartStore.cartItems,
                success_url: `https://vue-laravel-ecom-main-tpkbpw.laravel.cloud/success/payment/${hash}`,
            },
            {
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    Authorization: `Bearer ${user?.api_token}`,
                },
            }
        );
        if (response.data.url) {
            cartStore.setUniqueHash(hash);
            window.location.href = response.data.url;
        } else {
            console.error("URL not found in response:", response.data);
        }
    } catch (error) {
        console.error(error);
    }
};
</script>

<style scoped></style>
