<template>
    <Head title="Colors" />
    <adminLayout>
        <div class="grid grid-cols-1 gap-4 py-4 md:grid-cols-12">
            <div class="col-span-12 p-6 text-gray-900">
                <div class="flex items-center justify-between">
                    <h1 class="mb-2 text-2xl text-left text-black">
                        Colors: ({{ colorsCount }})
                    </h1>
                    <Link
                        class="px-4 py-2 text-white bg-gray-800 rounded-md"
                        :href="route('admin.colors.create')"
                        >+ New Color
                    </Link>
                </div>
                <hr class="my-2 border-t-2 border-indigo-700" />
            </div>
            <div class="flex justify-end col-span-12 p-6">
                <input
                    type="text"
                    placeholder="Search brands..."
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
                        <th class="p-2 text-center border">ID</th>
                        <th class="p-2 text-center border">Name</th>
                        <th class="p-2 text-center border">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="(color, index) in filteredColors"
                        :key="color.id"
                        :class="{
                            'bg-gray-200': index % 2 === 0,
                            'bg-white': index % 2 !== 0,
                        }"
                    >
                        <td class="p-2 text-center border">{{ index + 1 }}</td>
                        <td class="p-2 text-center border">
                            {{ color.id }}
                        </td>
                        <td
                            class="p-2 text-center text-gray-400 border"
                            :style="{ backgroundColor: color.name }"
                        >
                            {{ color.name }}
                        </td>
                        <td class="p-2 text-center border">
                            <button
                                class="px-4 py-2 mr-4 text-white bg-green-500 rounded-md"
                                @click="editColor(color)"
                            >
                                <span class="flex items-center">
                                    <PencilIcon class="w-5 h-5" />
                                </span>
                            </button>
                            <button
                                class="px-4 py-2 text-white bg-red-500 rounded-md"
                                @click="deleteColor(color)"
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

        <div v-if="colors.data.length > 0" class="flex justify-center my-4">
            <PaginationList :Links="colors.links" />
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

const props = defineProps({
    colors: Object,
    flash: Object,
});

const searchQuery = ref("");

const filteredColors = computed(() => {
    if (!searchQuery.value) {
        return props.colors.data;
    }
    return props.colors.data.filter((colors) =>
        colors.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const colorsCount = computed(() => props.colors.data.length);

const editColor = (color) => {
    router.visit(route("admin.colors.edit", color));
};

const deleteColor = (color) => {
    Swal.fire({
        title: "Are you sure you want to delete this color?",
        showCancelButton: true,
        confirmButtonText: "Delete",
        denyButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.colors.destroy", color.id), {
                onFinish: () => {
                    Swal.fire("Color deleted successfully", "", "success");
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
