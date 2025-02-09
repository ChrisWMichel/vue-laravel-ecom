<template>
    <div class="mx-4 mt-4 md:mx-24 md:mt-20">
        <div class="text-3xl font-bold text-center md:text-left">
            Cart Summary
        </div>
        <div class="mt-4 text-lg font-semibold">
            <div class="flex justify-between mb-4">
                <div>Total Products:</div>
                <div>{{ cartStore.getTotalProducts }}</div>
            </div>
            <div class="flex justify-between mb-4">
                <div>Total Quantity:</div>
                <div>{{ cartStore.getTotalQuantity }}</div>
            </div>
            <div class="flex justify-between">
                <div>Total Price:</div>
                <div class="text-green-800 text2xl">
                    <FormatPrice :price="cartStore.getTotalPrice" />
                </div>
            </div>
            <div class="flex justify-between mt-4">
                <Link class="standard-button" :href="route('home')">
                    Continue Shopping
                </Link>
                <Link
                    class="!bg-green-800 standard-button"
                    :href="route('checkout')"
                >
                    Checkout
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import FormatPrice from "@/Components/UI/FormatPrice.vue";
import { useCartStore } from "@/stores/useCartStore";
import { Link } from "@inertiajs/vue3";

const cartStore = useCartStore();
</script>

<style scoped></style>
