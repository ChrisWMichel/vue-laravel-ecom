<template>
    <div
        class="container px-6 py-6 mx-auto my-4 border-t border-b border-gray-200 sm:px-6 lg:px-8"
    >
        <div class="flex items-center space-x-4">
            <Link
                :href="route('profile.edit')"
                :class="{
                    'text-2xl font-semibold leading-tight text-gray-800 hover:text-gray-300': true,
                    '!text-fuchsia-700 !font-extrabold':
                        isActive('profile.edit'),
                }"
            >
                Profile
            </Link>
            <Link
                :href="route('profile.orders')"
                :class="{
                    'text-2xl font-semibold leading-tight text-gray-800 hover:text-gray-300': true,
                    '!text-fuchsia-700 !font-extrabold':
                        isActive('profile.orders'),
                }"
            >
                Order History
            </Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const isActive = (routeName) => {
    return route().current(routeName);
};
</script>

<style scoped></style>
