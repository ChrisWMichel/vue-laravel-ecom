<template>
    <div>
        <Head title="Create Product" />
        <adminLayout>
            <div class="grid grid-cols-1 gap-4 py-4 md:grid-cols-12">
                <div class="col-span-12 p-6 text-gray-900">
                    <h1 class="mb-2 text-2xl text-left text-black">
                        Create Product
                    </h1>
                    <Link
                        class="px-4 py-2 text-white bg-gray-800 rounded-md"
                        :href="route('admin.products.index')"
                        >Back
                    </Link>
                    <hr class="my-2 border-t-2 border-indigo-700" />
                </div>
            </div>
            <div class="flex flex-row items-center w-full">
                <form class="w-full p-6" @submit.prevent="submitForm">
                    <div>
                        <div class="mb-6">
                            <label
                                class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="in_stock"
                                >In Stock</label
                            >
                            <input
                                v-model="form.status"
                                class="block mt-1"
                                id="in_stock"
                                type="checkbox"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.status"
                            />
                        </div>
                        <div
                            class="flex flex-row justify-center w-full mb-4 space-x-4"
                        >
                            <div class="w-1/3 mb-4">
                                <label
                                    class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                    for="name"
                                    >Name</label
                                >
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
                            <div class="w-1/3 mb-4">
                                <label
                                    class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                    for="quantity"
                                    >Quantity</label
                                >
                                <input
                                    v-model="form.qty"
                                    class="block w-full px-4 py-3 leading-tight text-gray-700 border border-gray-200 rounded appearance-none bg-gray-50 focus:outline-none focus:bg-white"
                                    id="quantity"
                                    type="number"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.qty"
                                />
                            </div>
                            <div class="w-1/3 mb-4">
                                <label
                                    class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                    for="price"
                                    >Price</label
                                >
                                <input
                                    v-model="form.price"
                                    class="block w-full px-4 py-3 leading-tight text-gray-700 border border-gray-200 rounded appearance-none bg-gray-50 focus:outline-none focus:bg-white"
                                    id="price"
                                    type="number"
                                    step="0.01"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.price"
                                />
                            </div>
                        </div>
                        <div class="col-span-12">
                            <div class="mb-4">
                                <label
                                    class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                    for="description"
                                    >Description</label
                                >

                                <TextEditor v-model="form.desc" />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.desc"
                                />
                            </div>
                        </div>
                        <div
                            class="flex flex-row justify-center w-full mb-4 space-x-4"
                        >
                            <div class="w-1/2 mb-4">
                                <label
                                    class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                    for="category_id"
                                    >Category</label
                                >
                                <select
                                    v-model="form.category_id"
                                    class="block w-full px-4 py-3 leading-tight text-gray-700 border border-gray-200 rounded appearance-none bg-gray-50 focus:outline-none focus:bg-white"
                                    id="category_id"
                                >
                                    <option
                                        v-for="category in categories"
                                        :key="category.id"
                                        :value="category.id"
                                    >
                                        {{ category.name }}
                                    </option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.category_id"
                                />
                            </div>
                            <div class="w-1/2 mb-4">
                                <label
                                    class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                    for="brand_id"
                                    >Brand</label
                                >
                                <select
                                    v-model="form.brand_id"
                                    class="block w-full px-4 py-3 leading-tight text-gray-700 border border-gray-200 rounded appearance-none bg-gray-50 focus:outline-none focus:bg-white"
                                    id="brand_id"
                                >
                                    <option
                                        v-for="brand in brands"
                                        :key="brand.id"
                                        :value="brand.id"
                                    >
                                        {{ brand.name }}
                                    </option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.brand_id"
                                />
                            </div>
                        </div>
                        <div
                            class="flex flex-row justify-center w-full h-full mb-10 space-x-4"
                        >
                            <div class="w-1/4 mb-4 colors-section">
                                <label
                                    class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                    for="color_ids"
                                    >Colors</label
                                >
                                <select
                                    v-model="form.color_ids"
                                    class="block w-full px-4 py-3 leading-tight text-gray-700 border border-gray-200 rounded appearance-none bg-gray-50 focus:outline-none focus:bg-white"
                                    id="color_ids"
                                    multiple
                                >
                                    <option
                                        v-for="color in colors"
                                        :key="color.id"
                                        :value="color.id"
                                    >
                                        {{ color.name }}
                                    </option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.color_ids"
                                />
                            </div>
                            <div class="w-1/4 mb-4 sizes-section">
                                <label
                                    class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                    for="size_ids"
                                    >Sizes</label
                                >
                                <select
                                    v-model="form.size_ids"
                                    class="block w-full px-4 py-3 leading-tight text-gray-700 border border-gray-200 rounded appearance-none bg-gray-50 focus:outline-none focus:bg-white"
                                    id="size_ids"
                                    multiple
                                >
                                    <option
                                        v-for="size in sizes"
                                        :key="size.id"
                                        :value="size.id"
                                    >
                                        {{ size.name }}
                                    </option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.size_ids"
                                />
                            </div>

                            <!-- <div
                            class="flex flex-row justify-center w-full mb-4 space-x-4"
                        > -->
                            <div class="w-1/4 mb-4">
                                <label
                                    class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                    for="thumbnail"
                                    >Thumbnail</label
                                >
                                <input
                                    @change="handleThumbnailChange"
                                    class="block w-full px-4 py-3 leading-tight text-gray-700 border border-gray-200 rounded appearance-none bg-gray-50 focus:outline-none focus:bg-white"
                                    id="thumbnail"
                                    type="file"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.thumbnail"
                                />
                            </div>
                            <div class="w-1/4 mb-4">
                                <label
                                    class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                    for="images"
                                    >Images</label
                                >
                                <input
                                    @change="handleFileChange"
                                    class="block w-full px-4 py-3 leading-tight text-gray-700 border border-gray-200 rounded appearance-none bg-gray-50 focus:outline-none focus:bg-white"
                                    id="images"
                                    type="file"
                                    multiple
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.images"
                                />
                            </div>
                        </div>
                        <!-- </div> -->
                    </div>
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="px-4 py-2 text-white bg-gray-800 rounded-md"
                        >
                            Create
                        </button>
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
import TextEditor from "@/Components/TextEditor.vue";

const props = defineProps({
    categories: Array,
    brands: Array,
    colors: Array,
    sizes: Array,
});

const form = useForm({
    name: "",
    qty: null,
    price: null,
    desc: "",
    category_id: null,
    brand_id: null,
    color_ids: [],
    size_ids: [],
    thumbnail: null,
    first_image: null,
    second_image: null,
    third_image: null,
    status: true,
});
//const imageUrls = ref([]);
const handleFileChange = (event) => {
    const files = event.target.files;
    form.first_image = files[0] || null;
    form.second_image = files[1] || null;
    form.third_image = files[2] || null;
    //imageUrls.value = Array.from(files).map(file => URL.createObjectURL(file));
};

const handleThumbnailChange = (event) => {
    form.thumbnail = event.target.files[0] || null;
};

const submitForm = () => {
    console.log("Form submitted");
    form.post(route("admin.products.store"), {
        onFinish: () => {
            console.log("Form submission finished");
        },
        onError: (error) => {
            console.error("Error during form submission:", error);
        },
    });
};
</script>

<style scoped>
.colors-section select,
.sizes-section select {
    height: 200px;
}
</style>
