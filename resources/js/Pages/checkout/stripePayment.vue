<template>
    <div class="mt-10">
        <div class="text-2xl font-bold mb-4">Credit Card Information</div>
        <form @submit.prevent="handleSubmit">
            <div id="card-element" class="mb-4"></div>
            <button type="submit" class="standard-button">Pay Now</button>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
//import { loadStripe } from "@stripe/stripe-js";

onMounted(async () => {
    // const stripe = await stripePromise;
    // const elements = stripe.elements();
    // const cardElement = elements.create("card");
    // cardElement.mount("#card-element");
});

const handleSubmit = async () => {};
</script>

<style scoped></style>
