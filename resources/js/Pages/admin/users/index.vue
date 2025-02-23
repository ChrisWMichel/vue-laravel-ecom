<template>
    <Head title="Users" />
    <adminLayout>
        <div class="grid grid-cols-1 gap-4 py-4 md:grid-cols-12">
            <div class="col-span-12 p-6 text-gray-900">
                <div class="flex items-center justify-between">
                    <h1 class="mb-2 text-2xl text-left text-black">
                        Users: ({{ usersCount }})
                    </h1>
                </div>
                <hr class="my-2 border-t-2 border-indigo-700" />
            </div>
            <div class="flex justify-end col-span-12 p-6">
                <input
                    type="text"
                    placeholder="Search users..."
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
                        <th class="p-2 text-center border">Email</th>
                        <th class="p-2 text-center border">Profile Image</th>
                        <th class="p-2 text-center border">Registered</th>
                        <th class="p-2 text-center border">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="(user, index) in filteredUsers"
                        :key="user.id"
                        :class="{
                            'bg-gray-200': index % 2 === 0,
                            'bg-white': index % 2 !== 0,
                        }"
                    >
                        <td class="p-2 text-center border">{{ index + 1 }}</td>
                        <td class="p-2 text-center border">
                            {{ user.id }}
                        </td>
                        <td class="p-2 text-center text-gray-400 border">
                            {{ user.name }}
                        </td>
                        <td class="p-2 text-center text-gray-400 border">
                            {{ user.email }}
                        </td>
                        <td
                            class="flex p-2 text-center text-gray-400 border justify-self-center"
                        >
                            <img
                                v-if="user.profile_image"
                                :src="getThumbnailUrl(user.profile_image)"
                                alt="profile image"
                                class="w-10 h-10 rounded-full"
                            />
                        </td>
                        <td class="p-2 text-center border">
                            {{ user.created_at }}
                        </td>
                        <td class="p-2 text-center border">
                            <button
                                class="px-2 py-2 text-red-500 rounded-md"
                                @click="deleteUser(user)"
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

        <div v-if="users.data.length > 0" class="flex justify-center my-4">
            <PaginationList :Links="users.links" />
        </div>
    </adminLayout>
</template>

<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import adminLayout from "@/Layouts/adminLayout.vue";
import { PencilIcon, TrashIcon } from "@heroicons/vue/24/outline";
import PaginationList from "@/Components/UI/PaginationList.vue";
import Swal from "sweetalert2";
import { computed, ref } from "vue";

const props = defineProps({
    users: Object,
    flash: Object,
});

const searchQuery = ref("");

const filteredUsers = computed(() => {
    if (!searchQuery.value) {
        return props.users.data;
    }
    return props.users.data.filter((users) =>
        users.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const usersCount = computed(() => props.users.data.length);

const getThumbnailUrl = (thumbnail) => {
    return `/${thumbnail}`;
};

const deleteUser = (user) => {
    Swal.fire({
        title: "Are you sure you want to delete this user?",
        showCancelButton: true,
        confirmButtonText: "Delete",
        denyButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.user.delete", user.id), {
                onFinish: () => {
                    Swal.fire("User deleted successfully", "", "success");
                },
            });
        } else if (result.isDismissed) {
            Swal.fire("Changes were not saved", "", "info");
        }
    });
};
</script>

<style scoped></style>
