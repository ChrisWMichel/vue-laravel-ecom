<template>
    <div>
        <Head title="Edit Product" />
        <adminLayout>
            <div class="grid grid-cols-1 gap-4 py-4 md:grid-cols-12">
                <div class="col-span-12 p-6 text-gray-900">
                    <h1 class="mb-2 text-2xl text-left text-black">
                        Edit Product
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
                <!-- col-span-12 p-6 -->
                <form class="w-full p-6 -mt-5" @submit.prevent="submitForm">
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
                                <!-- <textarea
                                v-model="form.desc"
                                class="block w-full px-4 py-3 leading-tight text-gray-700 border border-gray-200 rounded appearance-none bg-gray-50 focus:outline-none focus:bg-white"
                                id="description"
                            ></textarea> -->
                                <TextEditor v-model="form.desc" />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.desc"
                                />
                            </div>
                        </div>
                        <!-- class="flex flex-row justify-center w-full mb-4 space-x-4" -->
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
                            class="flex flex-row justify-center w-full mb-4 space-x-4"
                        >
                            <div class="w-1/2 mb-4">
                                <label
                                    class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                    for="color_ids"
                                    >Colors</label
                                >
                                <div class="flex flex-wrap">
                                    <div
                                        v-for="color in colors"
                                        :key="color.id"
                                        class="flex items-center mb-2 mr-4"
                                    >
                                        <input
                                            type="checkbox"
                                            :id="'color_' + color.id"
                                            :value="color.id"
                                            v-model="form.color_ids"
                                            class="mr-2"
                                            :checked="
                                                form.color_ids.includes(
                                                    color.id
                                                )
                                            "
                                        />
                                        <label :for="'color_' + color.id">
                                            {{ color.name }}
                                        </label>
                                    </div>
                                </div>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.color_ids"
                                />
                            </div>
                            <div class="w-1/2 mb-4">
                                <label
                                    class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                    for="size_ids"
                                    >Sizes</label
                                >
                                <div class="flex flex-wrap">
                                    <div
                                        v-for="size in sizes"
                                        :key="size.id"
                                        class="flex items-center mb-2 mr-4"
                                    >
                                        <input
                                            type="checkbox"
                                            :id="'size_' + size.id"
                                            :value="size.id"
                                            v-model="form.size_ids"
                                            class="mr-2"
                                            :checked="
                                                form.size_ids.includes(size.id)
                                            "
                                        />
                                        <label :for="'size_' + size.id">
                                            {{ size.name }}
                                        </label>
                                    </div>
                                </div>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.size_ids"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center mt-12 space-x-40">
                        <div class="mb-4">
                            <label
                                class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="thumbnail"
                                >Thumbnail</label
                            >
                            <ImageManager
                                imageField="thumbnail"
                                :product="product"
                            />
                        </div>
                        <div class="mb-4">
                            <label
                                class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="images"
                                >Images</label
                            >
                            <ImageManager
                                imageField="first_image"
                                :product="product"
                                :disableButton="imagesDisabled"
                            />
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="px-4 py-2 text-white bg-gray-800 rounded-md"
                        >
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </adminLayout>
    </div>
</template>

<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import adminLayout from "@/Layouts/adminLayout.vue";
import InputError from "@/Components/InputError.vue";
import ImageManager from "@/Components/UI/ImageManager.vue";
import { ref, computed } from "vue";
import testingImageManager from "@/Components/UI/testingImageManager.vue";
import TextEditor from "@/Components/TextEditor.vue";

const props = defineProps({
    categories: Array,
    brands: Array,
    colors: Array,
    sizes: Array,
    product: Object,
});

const form = useForm({
    name: props.product.name,
    qty: props.product.qty,
    price: props.product.price,
    desc: props.product.desc,
    category_id: props.product.category_id,
    brand_id: props.product.brand_id,
    color_ids: props.product.colors
        ? props.product.colors.map((color) => color.id)
        : [],
    size_ids: props.product.sizes
        ? props.product.sizes.map((size) => size.id)
        : [],
    thumbnail: props.product.thumbnail,
    first_image: props.product.first_image || null,
    second_image: props.product.second_image || null,
    third_image: props.product.third_image || null,
    status: true,
});
const thumbnailDisabled = computed(() => !!props.product.thumbnail);
const imagesDisabled = computed(
    () =>
        !!props.product.first_image &&
        !!props.product.second_image &&
        !!props.product.third_image
);

const submitForm = () => {
    console.log(
        "Generated route: ",
        route("admin.products.update", props.product)
    );

    form.put(route("admin.products.update", props.product), {
        onFinish: () => {
            console.log("Form submission finished");
        },
        onError: (error) => {
            console.error("Error during form submission:", error);
        },
    });
};
</script>

<style></style>
