<template>
    <Head title="Success" />
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full mt-10 text-center">
            <Link :href="route('home')" alt="Logo" class="block mx-auto">
                <img
                    src="/logo-512x512.png"
                    width="160"
                    alt="Logo"
                    class="mx-auto"
                />
            </Link>
            <h1 class="text-2xl font-bold">Payment Success</h1>
            <p class="mt-5">
                Your payment was successful. Thank you for shopping with us.
            </p>
            <Link
                :href="route('home')"
                class="block w-1/4 mx-auto mt-5 standard-button"
            >
                Continue Shopping
            </Link>
        </div>
    </div>
</template>

<script setup>
import { useCartStore } from "@/stores/useCartStore";
import { BASE_URL } from "@/helpers/config";
import { usePage, Head, Link } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import axios from "axios";
import { onMounted } from "vue";

const cartStore = useCartStore();
const user = usePage().props.auth.user;
const toast = useToast();
const { props } = usePage();

const hash = props.hash;

const storeUserOrders = async () => {
    try {
        const response = await axios.post(
            `${BASE_URL}/store/order`,
            {
                cartItems: cartStore.cartItems,
                user_id: user.id,
            },
            {
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    Authorization: `Bearer ${user.api_token}`,
                },
            }
        );
        cartStore.clearCart();
        cartStore.setValidCoupon({
            name: "",
            discount: 0,
        });
        toast.success("Success! Check orders status in your profile tab.");
    } catch (error) {
        console.error(error);
    }
};

onMounted(() => {
    if (cartStore.uniqueHash === hash) {
        storeUserOrders();
        cartStore.setUniqueHash("");
    } else {
        toast.error("Invalid payment hash.");
        window.location.href = "/";
    }
});
</script>

<style scoped></style>
