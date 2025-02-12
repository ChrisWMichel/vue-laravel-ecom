<template>
    <div>
        <div>
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

                            <th class="p-2 text-center border">Color</th>
                            <th class="p-2 text-center border">Size</th>
                            <th class="p-2 text-center border">Price</th>
                            <th class="p-2 text-center border">Quantity</th>
                            <th class="p-2 text-center border">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="(product, index) in cartStore.cartItems"
                            :key="product.id"
                            :class="{
                                'bg-gray-200': index % 2 === 0,
                                'bg-white': index % 2 !== 0,
                            }"
                        >
                            <td class="p-2 text-center border">
                                <div class="flex items-center justify-center">
                                    <img
                                        class="w-8 h-8 mb-1 rounded-lg md:w-20 md:h-20"
                                        :src="getImageUrl(product.thumbnail)"
                                        :alt="product.name"
                                    />
                                </div>
                            </td>
                            <td class="p-2 text-center border">
                                {{ product.name }}
                            </td>

                            <td class="p-2 text-center border">
                                <div class="flex flex-col items-center">
                                    <span
                                        class="px-2 py-1 mb-1 text-white rounded-md"
                                        :style="{
                                            backgroundColor: product.color,
                                        }"
                                    >
                                        {{ product.color }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-2 text-center border">
                                <div class="flex flex-col items-center">
                                    <span
                                        class="px-2 py-1 mb-1 text-white bg-gray-600 rounded-md"
                                    >
                                        {{ product.size }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-2 text-center border">
                                <FormatPrice :price="product.price" />
                            </td>
                            <td class="p-2 text-center border">
                                {{ product.qty }}
                            </td>
                            <td class="p-2 text-center border">
                                <div class="flex flex-col items-center">
                                    <span class="px-2 py-1 mb-1">
                                        <FormatPrice
                                            :price="product.price * product.qty"
                                        />
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"></td>
                            <td class="p-2 text-center border">SubTotal</td>
                            <td colspan="2" class="p-2 text-center border">
                                <FormatPrice :price="cartStore.getTotalPrice" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td class="p-2 text-center border">tax (3.5%)</td>
                            <td colspan="2" class="p-2 text-center border">
                                <FormatPrice :price="taxes" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td class="p-2 text-center border">
                                Coupon ({{
                                    cartStore.validCoupon
                                        ? cartStore.validCoupon.discount
                                        : 0
                                }})%
                                <span
                                    v-if="cartStore.validCoupon"
                                    class="text-green-500"
                                    >{{ cartStore.validCoupon.name }}</span
                                >
                            </td>
                            <td colspan="2" class="p-2 text-center border">
                                <FormatPrice
                                    class="text-red-700"
                                    :price="coupon"
                                />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td class="p-2 font-bold text-center border">
                                Total
                            </td>
                            <td
                                colspan="2"
                                class="p-2 font-extrabold text-center border"
                            >
                                <FormatPrice :price="total" />
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useCartStore } from "@/stores/useCartStore";
import FormatPrice from "@/Components/UI/FormatPrice.vue";
import { computed } from "vue";

const cartStore = useCartStore();

const getImageUrl = (path) => {
    return path ? `${window.location.origin}/${path}` : "";
};

const taxes = computed(() => {
    return cartStore.getTotalPrice * 0.035;
});

// calculate discount
const coupon = computed(() => {
    return cartStore.getTotalPrice * (cartStore.validCoupon.discount / 100);
});
// calculate total
const total = computed(() => {
    return cartStore.getTotalPrice + taxes.value - coupon.value;
});
</script>

<style scoped></style>
