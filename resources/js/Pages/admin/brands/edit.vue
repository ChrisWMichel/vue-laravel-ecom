<template>
    <Head title="Edit Brand" />
    <adminLayout>
        <div class="grid grid-cols-1 gap-4 py-4 md:grid-cols-12">
            <div class="col-span-12 p-6 text-gray-900">
                <h1 class="mb-2 text-2xl text-left text-black">Edit Brand</h1>
                <Link
                    class="px-4 py-2 text-white bg-gray-800 rounded-md"
                    :href="route('admin.brands.index')"
                    >Cancel
                </Link>
                <hr class="my-2 border-t-2 border-indigo-700" />
            </div>
        </div>
        <div class="w-full max-w-lg p-6">
            <div class="flex flex-wrap mb-6 -mx-3">
                <form @submit.prevent="submitForm">
                    <input
                        class="px-4 py-2 border rounded-l-md"
                        v-model="form.name"
                        type="text"
                        placeholder="Brand Name"
                    />
                    <button
                        type="submit"
                        class="px-4 py-2 text-white bg-gray-800 rounded-r-md"
                    >
                        Update Brand
                    </button>
                </form>
                <InputError class="mt-2" :message="form.errors.name" />
            </div>
        </div>
    </adminLayout>
</template>

<script setup>
import { Head, usePage, useForm, Link } from "@inertiajs/vue3";
import adminLayout from "@/Layouts/adminLayout.vue";
import InputError from "@/Components/InputError.vue";

const { props } = usePage();
const brand = props.brand;

const form = useForm({
    name: brand.name,
});

const submitForm = () => {
    form.put(route("admin.brands.update", brand.slug));
};
</script>

<style></style>
