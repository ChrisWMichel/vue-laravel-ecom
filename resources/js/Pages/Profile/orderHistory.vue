<template>
    <Head title="Orders" />
    <!-- <userLayout> -->
    <profileNav />
    <div class="py-12">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="table w-full p-2">
                <table
                    class="min-w-full border-l border-r divide-y divide-gray-200 border-dark-100"
                >
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-2 text-center border">#</th>
                            <th></th>
                            <th class="p-2 text-center border">Product Name</th>
                            <th class="p-2 text-center border">Price</th>
                            <th class="p-2 text-center border">Qty</th>
                            <th class="p-2 text-center border">Total</th>
                            <th class="p-2 text-center border">Order Date</th>
                            <th class="p-2 text-center border">Delivered at</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="(order, index) in orders"
                            :key="order.id"
                            :class="{
                                'bg-gray-200': index % 2 === 0,
                                'bg-white': index % 2 !== 0,
                            }"
                        >
                            <td class="p-2 text-center border">
                                {{ index + 1 }}
                            </td>
                            <td class="p-2 text-center border">
                                <img
                                    v-if="order.products && order.products[0]"
                                    :src="
                                        getThumbnailUrl(
                                            order.products[0].thumbnail
                                        )
                                    "
                                    alt="Thumbnail"
                                    class="w-12"
                                />
                                <span v-else class="italic">No image</span>
                            </td>
                            <td class="p-2 text-center border">
                                <!-- <Link
                                    :href="`/products/${order.products[0].id}/show`"
                                > -->
                                {{ order.products[0].name }}
                                <!-- </Link> -->
                            </td>
                            <td class="p-2 text-center border">
                                <FormatPrice :price="order.products[0].price" />
                            </td>
                            <td class="p-2 text-center border">
                                {{ order.qty }}
                            </td>
                            <td class="p-2 text-center border">
                                <FormatPrice :price="order.total" />
                            </td>
                            <td class="p-2 text-center border">
                                {{ order.created_at }}
                            </td>
                            <td class="p-2 text-center border">
                                <span v-if="order.delivered_at">
                                    {{ order.delivered_at }}
                                </span>
                                <i v-else class="italic">Pending...</i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- </userLayout> -->
</template>

<script setup>
import userLayout from "@/Layouts/userLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { defineProps, onMounted, ref } from "vue";
import FormatPrice from "@/Components/UI/FormatPrice.vue";
import profileNav from "./profileNav.vue";

const props = defineProps({
    orders: Array,
});

onMounted(() => {
    // Simulate data loading
    setTimeout(() => {
        isLoading.value = false;
    }, 3000);
});

console.log("orders", props.orders);
const getThumbnailUrl = (thumbnail) => {
    return `/${thumbnail}`;
};
</script>

<style scoped></style>
