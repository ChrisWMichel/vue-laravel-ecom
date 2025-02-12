<template>
    <Head title="Edit Coupon" />
    <adminLayout>
        <div class="grid grid-cols-1 gap-4 py-4 md:grid-cols-12">
            <div class="col-span-12 p-6 text-gray-900">
                <h1 class="mb-2 text-2xl text-left text-black">Edit Coupon</h1>
                <Link
                    class="px-4 py-2 text-white bg-gray-800 rounded-md"
                    :href="route('admin.coupons.index')"
                    >Cancel
                </Link>
                <hr class="my-2 border-t-2 border-indigo-700" />
            </div>
        </div>
        <div>
            <div class="w-3/4">
                <form @submit.prevent="submitForm">
                    <div class="flex space-x-2">
                        <div class="w-full px-3 mb-6 md:w-1/2 md:mb-0">
                            <label
                                class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="name"
                                >Name
                            </label>
                            <input
                                class="px-4 py-2 border rounded-l-md"
                                v-model="form.name"
                                type="text"
                                placeholder="Category Name"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.name"
                            />
                        </div>

                        <div class="w-full px-3 mb-6 md:w-1/2 md:mb-0">
                            <label
                                class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="name"
                                >Discount
                            </label>
                            <input
                                class="px-4 py-2 border rounded-l-md"
                                v-model="form.discount"
                                type="number"
                                placeholder="Discount"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.discount"
                            />
                        </div>
                        <div class="w-full px-3 mb-6 md:w-1/2 md:mb-0">
                            <label
                                class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="name"
                                >Experation Date
                            </label>
                            <input
                                class="px-4 py-2 border rounded-l-md"
                                v-model="form.valid_until"
                                type="date"
                                placeholder="Experation Date"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.valid_until"
                            />
                        </div>
                        <div class="w-full mt-5">
                            <div class="px-3 md:mb-0">
                                <button
                                    type="submit"
                                    class="px-6 py-3 w-full text-white bg-gray-800 rounded-md"
                                >
                                    Update Coupon
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </adminLayout>
</template>

<script setup>
import { Head, usePage, useForm, Link } from "@inertiajs/vue3";
import adminLayout from "@/Layouts/adminLayout.vue";
import InputError from "@/Components/InputError.vue";

const { props } = usePage();
const coupon = props.coupon;

const form = useForm({
    name: coupon.name,
    discount: coupon.discount,
    valid_until: coupon.valid_until,
});

const submitForm = () => {
    form.put(route("admin.coupons.update", coupon.id));
};
</script>

<style scoped></style>
