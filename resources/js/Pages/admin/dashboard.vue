<template>
    <Head title="Dashboard" />
    <adminLayout>
        <div class="grid grid-cols-1 gap-4 py-4 md:grid-cols-12">
            <div class="col-span-12 p-6 text-gray-900">
                <h1 class="text-2xl text-left text-black">Dashboard</h1>
                <hr class="my-2 border-t-2 border-indigo-700" />
            </div>

            <div class="col-span-12 mb-2 md:col-span-6">
                <div
                    class="p-3 text-gray-900 bg-white border border-gray-400 rounded-lg"
                >
                    <BoxShadow>
                        <div class="flex justify-between mb-2">
                            <h2
                                class="p-1 text-center text-gray-300 bg-black rounded-lg"
                            >
                                Today's Orders
                            </h2>
                            <p
                                class="p-1 font-extrabold text-center text-gray-300 bg-black rounded-lg"
                            >
                                {{ todayOrders.length }}
                            </p>
                        </div>
                        <hr />
                        <div class="mt-2 text-center text-black">
                            Total:
                            <span
                                class="p-1 font-extrabold text-center text-gray-300 bg-black rounded-lg"
                            >
                                ${{ todayOrders.total }}</span
                            >
                        </div>
                    </BoxShadow>
                </div>
            </div>
            <!-- Add other grid items here -->
            <div class="col-span-12 mb-2 md:col-span-6">
                <div
                    class="p-3 text-gray-900 bg-white border border-gray-400 rounded-lg"
                >
                    <BoxShadow>
                        <div class="flex justify-between mb-2">
                            <h2
                                class="p-1 text-center text-black bg-blue-400 rounded-lg"
                            >
                                Yesterday's Orders
                            </h2>
                            <p
                                class="p-1 font-extrabold text-center text-black bg-blue-400 rounded-lg"
                            >
                                {{ yesterdayOrders.length }}
                            </p>
                        </div>
                        <hr />
                        <div class="mt-2 text-center text-black">
                            Total:
                            <span
                                class="p-1 text-center text-black bg-blue-400 rounded-lg"
                            >
                                ${{ yesterdayOrders.total }}</span
                            >
                        </div>
                    </BoxShadow>
                </div>
            </div>
            <div class="col-span-12 mb-2 md:col-span-6">
                <div
                    class="p-3 text-gray-900 bg-white border border-gray-400 rounded-lg"
                >
                    <BoxShadow>
                        <div class="flex justify-between mb-2">
                            <h2
                                class="p-1 text-center text-black bg-orange-400 rounded-lg"
                            >
                                Month's Orders
                            </h2>
                            <p
                                class="p-1 font-extrabold text-center text-black bg-orange-400 rounded-lg"
                            >
                                {{ monthsOrders.length }}
                            </p>
                        </div>
                        <hr />
                        <div class="mt-2 text-center text-black">
                            Total:
                            <span
                                class="p-1 text-center text-black bg-orange-400 rounded-lg"
                            >
                                ${{ monthsOrders.total }}</span
                            >
                        </div>
                    </BoxShadow>
                </div>
            </div>
            <div class="col-span-12 mb-2 md:col-span-6">
                <div
                    class="p-3 text-gray-900 bg-white border border-gray-400 rounded-lg"
                >
                    <BoxShadow>
                        <div class="flex justify-between mb-2">
                            <h2
                                class="p-1 text-center text-black bg-green-400 rounded-lg"
                            >
                                Year's Orders
                            </h2>
                            <p
                                class="p-1 font-extrabold text-center text-black bg-green-400 rounded-lg"
                            >
                                {{ yearOrders.length }}
                            </p>
                        </div>
                        <hr />
                        <div class="mt-2 text-center text-black">
                            Total:
                            <span
                                class="p-1 text-center text-black bg-green-400 rounded-lg"
                            >
                                ${{ yearOrders.total }}</span
                            >
                        </div>
                    </BoxShadow>
                </div>
            </div>
        </div>
    </adminLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import adminLayout from "@/Layouts/adminLayout.vue";
import BoxShadow from "@/Components/UI/BoxShadow.vue";
const props = defineProps({
    todayOrders: {
        type: Array,
    },
    yesterdayOrders: {
        type: Array,
    },
    monthsOrders: {
        type: Array,
    },
    yearOrders: {
        type: Array,
    },
    admin: {
        type: Object,
    },
});
</script>
