<template>
    <div class="flex">
        <span
            v-for="star in 5"
            :key="star"
            class="star"
            :class="{ 'text-gold': star <= rating }"
        >
            ★
        </span>
    </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    rating: {
        type: Number,
        required: true,
    },
});
</script>

<style scoped>
.star {
    font-size: 1.5rem;
    color: #ccc; /* Default star color */
}
.text-gold {
    color: gold; /* Filled star color */
}
</style>
