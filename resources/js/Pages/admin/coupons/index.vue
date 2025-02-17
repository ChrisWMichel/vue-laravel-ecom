<template>
    <Head title="Coupons" />
    <adminLayout>
        <div class="grid grid-cols-1 gap-4 py-4 md:grid-cols-12">
            <div class="col-span-12 p-6 text-gray-900">
                <div class="flex items-center justify-between">
                    <h1 class="mb-2 text-2xl text-left text-black">
                        Coupons: ({{ couponCount }})
                    </h1>
                    <Link
                        class="px-4 py-2 text-white bg-gray-800 rounded-md"
                        :href="route('admin.coupons.create')"
                        >+ New Coupon
                    </Link>
                </div>
                <hr class="my-2 border-t-2 border-indigo-700" />
            </div>
            <div class="flex justify-end col-span-12 p-6">
                <input
                    type="text"
                    placeholder="Search coupons..."
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
                        <th class="p-2 text-center border">Discount</th>
                        <th class="p-2 text-center border">Experation Date</th>
                        <th class="p-2 text-center border">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="(coupon, index) in filteredCoupons"
                        :key="coupon.id"
                        :class="{
                            'bg-gray-200': index % 2 === 0,
                            'bg-white': index % 2 !== 0,
                        }"
                    >
                        <td class="p-2 text-center border">{{ index + 1 }}</td>

                        <td class="p-2 text-center border">
                            {{ coupon.name }}
                        </td>
                        <td class="p-2 text-center border">
                            {{ coupon.discount }}
                        </td>
                        <td
                            v-if="coupon.is_valid"
                            class="p-2 text-center border"
                        >
                            <span
                                class="px-2 py-1 text-white bg-green-500 rounded-md"
                                >Valid until
                                {{ formatDate(coupon.valid_until) }}</span
                            >
                        </td>
                        <td v-else class="p-2 text-center border">
                            <span
                                class="px-2 py-1 text-white bg-red-500 rounded-md"
                                >Expired on
                                {{ formatDate(coupon.valid_until) }}</span
                            >
                        </td>
                        <td class="p-2 text-center border">
                            <button
                                class="px-2 py-2 mr-4 text-green-500 rounded-md"
                                @click="editCoupon(coupon)"
                            >
                                <span class="flex items-center">
                                    <PencilIcon class="w-5 h-5" />
                                </span>
                            </button>
                            <button
                                class="px-2 py-2 text-red-500 rounded-md"
                                @click="deleteCoupon(coupon)"
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
    </adminLayout>
</template>

<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import adminLayout from "@/Layouts/adminLayout.vue";
import { PencilIcon, TrashIcon } from "@heroicons/vue/24/outline";
import Swal from "sweetalert2";
import { computed, ref, onMounted } from "vue";
import { format } from "date-fns";

const props = defineProps({
    coupons: Object,
    flash: Object,
});

const searchQuery = ref("");

const filteredCoupons = computed(() => {
    if (!searchQuery.value) {
        return props.coupons;
    }
    return props.coupons.filter((coupons) =>
        coupons.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const couponCount = computed(() => props.coupons.length);

const formatDate = (date) => {
    return format(new Date(date), "MM-dd-yyyy");
};

const editCoupon = (coupon) => {
    router.visit(route("admin.coupons.edit", coupon));
};

const deleteCoupon = (coupon) => {
    Swal.fire({
        title: "Are you sure you want to delete this coupon?",
        showCancelButton: true,
        confirmButtonText: "Delete",
        denyButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.coupons.destroy", coupon), {
                onFinish: () => {
                    Swal.fire("Coupon deleted successfully", "", "success");
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
