<template>
    <Head title="Cart" />
    <userLayout>
        <div v-if="cartItems.length === 0">
            <div class="flex items-center justify-center h-96">
                <h1 class="text-2xl font-semibold text-gray-800">
                    Your cart is empty
                </h1>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
            <div
                class="table w-full col-span-1 p-2 px-4 mt-4 md:col-span-8 md:px-10"
            >
                <table
                    class="min-w-full border-l border-r divide-y divide-gray-200 border-dark-100"
                >
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-2 text-center border"></th>
                            <th class="p-2 text-center border"></th>

                            <th class="p-2 text-center border">Price</th>
                            <th class="p-2 text-center border">Colors</th>
                            <th class="p-2 text-center border">Sizes</th>

                            <th class="p-2 text-center border">Total</th>
                            <th class="p-2 text-center border">Quantity</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="(product, index) in cartItems"
                            :key="product.id"
                            :class="{
                                'bg-gray-200': index % 2 === 0,
                                'bg-white': index % 2 !== 0,
                            }"
                        >
                            <cartItem
                                :product="product"
                                @removeItem="removeItem(product)"
                            />
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-span-1 md:col-span-4">
                <cartSummary />
            </div>
        </div>
    </userLayout>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { Head } from "@inertiajs/vue3";
import cartItem from "./cartItem.vue";
import { useCartStore } from "@/stores/useCartStore";
import userLayout from "@/Layouts/userLayout.vue";
import cartSummary from "./cartSummary.vue";

const cartStore = useCartStore();
const cartItems = ref([]);

onMounted(() => {
    cartItems.value = localStorage.getItem("cartItems");
    if (cartItems.value) {
        cartItems.value = JSON.parse(cartItems.value);
    }
});

const removeItem = (product) => {
    cartStore.removeFromCart(product);

    cartItems.value = cartItems.value.filter(
        (item) => item.ref !== product.ref
    );
};
</script>

<style scoped></style>
