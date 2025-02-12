<template>
    <div>
        <Head title="Create Coupon" />
        <adminLayout>
            <div class="grid grid-cols-1 gap-4 py-4 md:grid-cols-12">
                <div class="col-span-12 p-6 text-gray-900">
                    <h1 class="mb-2 text-2xl text-left text-black">
                        Create Coupon
                    </h1>
                    <Link
                        class="px-4 py-2 text-white bg-gray-800 rounded-md"
                        :href="route('admin.coupons.index')"
                        >Back
                    </Link>
                    <hr class="my-2 border-t-2 border-indigo-700" />
                </div>
            </div>
            <div>
                <form class="w-full max-w-lg p-6" @submit.prevent="submitForm">
                    <div class="flex flex-wrap mb-6 -mx-3">
                        <div class="w-full px-3 mb-6 md:w-1/2 md:mb-0">
                            <label
                                class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="name"
                                >Name
                            </label>
                            <input
                                v-model="form.name"
                                class="block w-full px-4 py-3 leading-tight text-gray-700 border border-gray-200 rounded appearance-none bg-gray-50 focus:outline-none focus:bg-white"
                                id="name"
                                type="text"
                                autofocus
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.name"
                            />
                        </div>
                    </div>
                    <div class="flex flex-wrap mb-6 -mx-3">
                        <div class="w-full px-3 mb-6 md:w-1/2 md:mb-0">
                            <label
                                class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="name"
                                >Discount
                            </label>
                            <input
                                v-model="form.discount"
                                class="block w-full px-4 py-3 leading-tight text-gray-700 border border-gray-200 rounded appearance-none bg-gray-50 focus:outline-none focus:bg-white"
                                id="discount"
                                type="number"
                                autofocus
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.discount"
                            />
                        </div>
                    </div>
                    <div class="flex flex-wrap mb-6 -mx-3">
                        <div class="w-full px-3 mb-6 md:w-1/2 md:mb-0">
                            <label
                                class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="name"
                                >Valid Until
                            </label>
                            <input
                                v-model="form.valid_until"
                                class="block w-full px-4 py-3 leading-tight text-gray-700 border border-gray-200 rounded appearance-none bg-gray-50 focus:outline-none focus:bg-white"
                                id="discount"
                                type="date"
                                autofocus
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.valid_until"
                            />
                        </div>
                    </div>
                    <div class="flex flex-wrap mb-6 -mx-3">
                        <div class="w-full px-3 mb-6 md:w-1/2 md:mb-0">
                            <button
                                type="submit"
                                class="px-4 py-2 text-white bg-gray-800 rounded-md"
                            >
                                Create
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </adminLayout>
    </div>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import adminLayout from "@/Layouts/adminLayout.vue";
import InputError from "@/Components/InputError.vue";

const form = useForm({
    name: "",
    discount: "",
    valid_until: "",
});

const submitForm = () => {
    form.post(route("admin.coupons.store"), {
        onFinish: () => {
            console.log("Form submission finished");
        },
        onError: (error) => {
            console.error("Error during form submission:", error);
        },
    });
};
</script>

<style scoped></style>
