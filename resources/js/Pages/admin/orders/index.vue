<template>
    <Head title="Orders" />
    <adminLayout>
        <div class="grid grid-cols-1 gap-4 py-4 md:grid-cols-12">
            <div class="col-span-12 p-6 text-gray-900">
                <div class="flex items-center justify-between">
                    <h1 class="mb-2 text-2xl text-left text-black">
                        Orders: ({{ totalOrders }})
                    </h1>
                </div>
                <hr class="my-2 border-t-2 border-indigo-700" />
            </div>
            <div class="flex justify-end col-span-12 p-6">
                <input
                    type="text"
                    placeholder="Search orders..."
                    class="px-4 py-2 border rounded-md"
                    v-model="searchQuery"
                />
            </div>
        </div>
        <div class="table w-full p-2">
            <table
                class="min-w-full border-l border-r divide-y divide-gray-200 border-dark-100"
            >
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-2 text-center border">#</th>
                        <th class="p-2 text-center border">Name</th>
                        <th class="p-2 text-center border">Price</th>
                        <th class="p-2 text-center border">By</th>
                        <th class="p-2 text-center border">Coupon</th>
                        <th class="p-2 text-center border">Qty</th>
                        <th class="p-2 text-center border">Total</th>
                        <th class="p-2 text-center border">Order Date</th>
                        <th class="p-2 text-center border">Delivered at</th>
                        <th class="p-2 text-center border">Delete</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="(order, index) in filteredOrders"
                        :key="order.id"
                        :class="{
                            'bg-gray-200': index % 2 === 0,
                            'bg-white': index % 2 !== 0,
                        }"
                    >
                        <td class="p-2 text-center border">{{ index + 1 }}</td>
                        <td class="p-2 text-center border">
                            {{ order.products[0].name }}
                        </td>
                        <td class="p-2 text-center border">
                            {{ order.price }}
                        </td>
                        <td class="p-2 text-center border">
                            {{ order.user.name }}
                        </td>
                        <td class="p-2 text-center border">
                            <span
                                v-if="order.coupon"
                                class="px-2 py-1 text-gray-700 bg-green-300 rounded-md"
                                >{{ order.coupon.name }} '('{{
                                    order.coupon.discount
                                }}')'</span
                            >
                            <span
                                v-else
                                class="px-2 py-1 text-gray-700 bg-red-300 rounded-md"
                                >No Coupon</span
                            >
                        </td>
                        <td class="p-2 text-center border">
                            {{ order.qty }}
                        </td>

                        <td class="p-2 text-center border">
                            <format-price :price="order.total" />
                        </td>
                        <td class="p-2 text-center border">
                            {{ order.created_at }}
                        </td>
                        <td class="text-center border">
                            <span
                                v-if="order.delivered_at"
                                class="px-2 py-1 text-gray-700 bg-green-300 rounded-md"
                                >{{ order.delivered_at }}</span
                            >
                            <span
                                v-else
                                class="flex flex-row items-center px-2 py-2 italic text-gray-700"
                                >Pending...
                                <Link
                                    :href="route('admin.orders.update', order)"
                                    class="text-green-500"
                                >
                                    <span class="flex items-center">
                                        <PencilIcon class="w-5 h-5" />
                                    </span>
                                </Link>
                            </span>
                        </td>
                        <td class="p-2 text-center border">
                            <button
                                class="px-2 py-2 text-red-500 rounded-md"
                                @click="deleteOrder(order)"
                            >
                                <span class="flex items-center">
                                    <TrashIcon class="w-5 h-5" />
                                </span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="orders.data.length > 0" class="flex justify-center my-4">
            <PaginationList :Links="orders.links" />
        </div>
    </adminLayout>
</template>

<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import adminLayout from "@/Layouts/adminLayout.vue";
import { PencilIcon, TrashIcon } from "@heroicons/vue/24/outline";
import PaginationList from "@/Components/UI/PaginationList.vue";
import FormatPrice from "@/Components/UI/FormatPrice.vue";
import Swal from "sweetalert2";
import { computed, ref, onMounted } from "vue";

const props = defineProps({
    orders: Object,
    flash: Object,
    totalOrders: Number,
});
//console.log("orders", props.orders.data);
const searchQuery = ref("");

const filteredOrders = computed(() => {
    if (!searchQuery.value) {
        return props.orders.data;
    }
    return props.orders.data.filter((order) =>
        order.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const deleteOrder = (order) => {
    Swal.fire({
        title: "Are you sure you want to delete this order?",
        showCancelButton: true,
        confirmButtonText: "Delete",
        denyButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.orders.delete", order), {
                onFinish: () => {
                    Swal.fire("Order deleted successfully", "", "success");
                },
            });
        } else if (result.isDismissed) {
            Swal.fire("Changes are not saved", "", "info");
        }
    });
};
onMounted(() => {
    try {
        if (props.flash.success) {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: props.flash.success,
                showConfirmButton: false,
                timer: 2000,
            }).then(() => {
                props.flash.success = null;
            });
        }
    } catch (error) {
        console.error("Error in mounted hook:", error);
    }
});
</script>

<style scoped></style>
