<template>
    <Head title="Products" />
    <adminLayout>
        <div class="grid grid-cols-1 gap-4 py-4 md:grid-cols-12">
            <div class="col-span-12 p-6 text-gray-900">
                <div class="flex items-center justify-between">
                    <h1 class="mb-2 text-2xl text-left text-black">
                        Products: ({{ productCount }})
                    </h1>
                    <Link
                        class="px-4 py-2 text-white bg-gray-800 rounded-md"
                        :href="route('admin.products.create')"
                        >+ New Product
                    </Link>
                </div>
                <hr class="my-2 border-t-2 border-indigo-700" />
            </div>
            <div class="flex justify-end col-span-12 p-6">
                <input
                    type="text"
                    placeholder="Search categories..."
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
                        <th class="p-2 text-center border">ID</th>
                        <th class="p-2 text-center border">Status</th>
                        <th class="p-2 text-center border">Name</th>
                        <th class="p-2 text-center border">Quantity</th>
                        <th class="p-2 text-center border">Price</th>
                        <th class="p-2 text-center border">Description</th>
                        <th class="p-2 text-center border">Category</th>
                        <th class="p-2 text-center border">Brand</th>
                        <th class="p-2 text-center border">Colors</th>
                        <th class="p-2 text-center border">Sizes</th>
                        <th class="p-2 text-center border">Thumbnail</th>
                        <th class="p-2 text-center border">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="(product, index) in filteredProducts"
                        :key="product.id"
                        :class="{
                            'bg-gray-200': index % 2 === 0,
                            'bg-white': index % 2 !== 0,
                        }"
                    >
                        <td class="p-2 text-center border">
                            {{ product.id }}
                        </td>
                        <td class="p-2 text-center border">
                            <span
                                v-if="product.status"
                                class="px-2 py-1 text-white bg-green-500 rounded-md"
                            >
                                In Stock
                            </span>
                            <span
                                v-else
                                class="px-2 py-1 text-white bg-red-500 rounded-md"
                            >
                                Out of Stock
                            </span>
                        </td>
                        <td class="p-2 text-center border">
                            {{ product.name }}
                        </td>
                        <td class="p-2 text-center border">
                            {{ product.qty }}
                        </td>
                        <td class="p-2 text-center border">
                            <FormatPrice :price="product.price" />
                        </td>
                        <td class="w-64 p-2 text-center border">
                            {{ product.desc }}
                        </td>
                        <td class="p-2 text-center border">
                            {{ product.category.name }}
                        </td>
                        <td class="p-2 text-center border">
                            {{ product.brand.name }}
                        </td>
                        <td class="p-2 text-center border">
                            <div class="flex flex-col items-center">
                                <span
                                    v-for="color in product.colors"
                                    :key="color.id"
                                    class="px-2 py-1 mb-1 text-white rounded-md"
                                    :style="{ backgroundColor: color.name }"
                                >
                                    {{ color.name }}
                                </span>
                            </div>
                        </td>
                        <td class="p-2 text-center border">
                            <div class="flex flex-col items-center">
                                <span
                                    v-for="size in product.sizes"
                                    :key="size.id"
                                    class="px-2 py-1 mb-1 text-white bg-gray-600 rounded-md"
                                >
                                    {{ size.name }}
                                </span>
                            </div>
                        </td>
                        <td class="p-2 text-center border">
                            <img
                                class="w-20 h-20 mb-1 rounded-lg"
                                :src="getImageUrl(product.thumbnail)"
                                alt="{{ product.name }}"
                            />
                            <!-- <span v-if="product.first_image">
                                <img
                                    class="w-20 h-20 mb-1 rounded-lg"
                                    :src="getImageUrl(product.first_image)"
                                    alt="{{ product.name }}"
                                />
                            </span>
                            <span v-if="product.second_image">
                                <img
                                    class="w-20 h-20 mb-1 rounded-lg"
                                    :src="getImageUrl(product.second_image)"
                                    alt="{{ product.name }}"
                                />
                            </span> -->
                        </td>
                        <td class="p-2 text-center border">
                            <button
                                class="px-2 py-2 mr-4 text-green-500 rounded-md"
                                @click="editProduct(product)"
                            >
                                <span class="flex items-center">
                                    <PencilIcon class="w-5 h-5" />
                                </span>
                            </button>
                            <button
                                class="px-2 py-2 text-red-500 rounded-md"
                                @click="deleteProduct(product)"
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

        <div v-if="products.data.length > 0" class="flex justify-center my-4">
            <PaginationList :Links="products.links" />
        </div>
    </adminLayout>
</template>

<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import adminLayout from "@/Layouts/adminLayout.vue";
import { PencilIcon, TrashIcon } from "@heroicons/vue/24/outline";
import PaginationList from "@/Components/UI/PaginationList.vue";
import Swal from "sweetalert2";
import { computed, ref, onMounted } from "vue";
import FormatPrice from "@/Components/UI/FormatPrice.vue";

const props = defineProps({
    products: Object,
    flash: Object,
});
// 'colors', 'sizes', 'category', 'brand'
const searchQuery = ref("");

const filteredProducts = computed(() => {
    if (!searchQuery.value) {
        return props.products.data;
    }
    return props.products.data.filter((product) =>
        product.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const productCount = computed(() => props.products.data.length);

const getImageUrl = (path) => {
    return path ? `${window.location.origin}/${path}` : "";
};

const editProduct = (product) => {
    router.visit(route("admin.products.edit", product));
    //router.visit(route("admin.products.edit", { product: product.id }));
};

const deleteProduct = (product) => {
    Swal.fire({
        title: "Are you sure you want to delete this product?",
        showCancelButton: true,
        confirmButtonText: "Delete",
        denyButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.products.destroy", product), {
                onFinish: () => {
                    Swal.fire("Product deleted successfully", "", "success");
                },
            });
        } else if (result.isDismissed) {
            Swal.fire("Changes were not saved", "", "info");
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
