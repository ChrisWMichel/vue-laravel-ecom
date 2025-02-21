<template>
    <div class="px-2 py-4 text-gray-200 bg-indigo-700 rounded-l-2xl">
        <Link
            :href="route('admin.index')"
            class="flex items-center p-4 transition-colors rounded hover:bg-black/30"
        >
            <span class="mr-2 text-gray-300"><HomeIcon class="w-5" /></span>
            <span>Dashboard</span>
        </Link>

        <Link
            :href="route('admin.categories.index')"
            class="flex items-center p-4 transition-colors rounded hover:bg-black/30"
        >
            <span class="mr-2 text-gray-300"
                ><NumberedListIcon class="w-5"
            /></span>
            <span>Categories</span>
        </Link>
        <Link
            :href="route('admin.brands.index')"
            class="flex items-center p-4 transition-colors rounded hover:bg-black/30"
        >
            <span class="mr-2 text-gray-300"
                ><PaintBrushIcon class="w-5"
            /></span>
            <span>Brands</span>
        </Link>
        <Link
            :href="route('admin.colors.index')"
            class="flex items-center p-4 transition-colors rounded hover:bg-black/30"
        >
            <span class="mr-2 text-gray-300"><SwatchIcon class="w-5" /></span>
            <span>Colors</span>
        </Link>
        <Link
            :href="route('admin.sizes.index')"
            class="flex items-center p-4 transition-colors rounded hover:bg-black/30"
        >
            <span class="mr-2 text-gray-300"><PencilIcon class="w-5" /></span>
            <span>Sizes</span>
        </Link>
        <Link
            :href="route('admin.products.index')"
            class="flex items-center p-4 transition-colors rounded hover:bg-black/30"
        >
            <span class="mr-2 text-gray-300"><TagIcon class="w-5" /></span>
            <span>Products</span>
        </Link>
        <div class="text-center my20">--</div>
        <Link
            :href="route('admin.coupons.index')"
            class="flex items-center p-4 transition-colors rounded hover:bg-black/30"
        >
            <span class="mr-2 text-gray-300"><TicketIcon class="w-5" /></span>
            <span>Coupons</span>
        </Link>
        <Link
            :href="route('admin.orders.index')"
            class="flex items-center p-4 transition-colors rounded hover:bg-black/30"
        >
            <span class="mr-2 text-gray-300"
                ><ShoppingBagIcon class="w-5"
            /></span>
            <span>Orders</span>
        </Link>
        <Link
            :href="route('admin.reviews.index')"
            class="flex items-center p-4 transition-colors rounded hover:bg-black/30"
        >
            <span class="mr-2 text-gray-300"
                ><PaperClipIcon class="w-5"
            /></span>
            <span>Reviews</span>
        </Link>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import {
    HomeIcon,
    UserIcon,
    ChartBarIcon,
    ShoppingCartIcon,
    NumberedListIcon,
    PaintBrushIcon,
    SwatchIcon,
    PencilIcon,
    TagIcon,
    TicketIcon,
    ShoppingBagIcon,
    PaperClipIcon,
} from "@heroicons/vue/24/outline";
</script>

<style></style>
