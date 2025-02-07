<template>
    <Head title="Edit Color" />
    <adminLayout>
        <div class="grid grid-cols-1 gap-4 py-4 md:grid-cols-12">
            <div class="col-span-12 p-6 text-gray-900">
                <h1 class="mb-2 text-2xl text-left text-black">Edit Color</h1>
                <Link
                    class="px-4 py-2 text-white bg-gray-800 rounded-md"
                    :href="route('admin.colors.index')"
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
                        placeholder="Color Name"
                    />
                    <button
                        type="submit"
                        class="px-4 py-2 text-white bg-gray-800 rounded-r-md"
                    >
                        Update Color
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
const color = props.color;

const form = useForm({
    name: color.name,
});

const submitForm = () => {
    form.put(route("admin.colors.update", color.id));
};
</script>

<style></style>
