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
                            :src="user.profile_image ? 
                                (user.profile_image.includes('amazonaws.com') ? 
                                    (user.profile_image.includes('https://') ? 
                                    user.profile_image.substring(user.profile_image.indexOf('https://')) : 
                                    user.profile_image) : 
                                    profileImageUrl(user.profile_image)) : 
                                '/default-profile.png'"
                            class="w-10 h-10 rounded-full"
                            alt="profile image"
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
import { computed, ref, onMounted, nextTick } from "vue";

const props = defineProps({
    users: Object,
    flash: Object,
});

const searchQuery = ref("");

const fixS3ImageUrls = () => {
  // Find all images with S3 URLs
  const images = document.querySelectorAll('img[src*="amazonaws.com"]');
  
  images.forEach(img => {
    const currentSrc = img.getAttribute('src');
    if (currentSrc) {
      // Find the position of https:// in the string
      const httpsPos = currentSrc.indexOf('https://');
      console.log('httpsPos', httpsPos);
      if (httpsPos > 0) {
        // Extract only from https:// onwards
        const fixedSrc = currentSrc.substring(httpsPos);
        console.log('fixedSrc', fixedSrc);
        img.setAttribute('src', fixedSrc);
        console.log('Fixed S3 image URL:', fixedSrc);
      }
    }
  });
};

onMounted(() => {
  nextTick(() => {
    fixS3ImageUrls();
  });
});
const filteredUsers = computed(() => {
    if (!searchQuery.value) {
        return props.users.data;
    }
    return props.users.data.filter((users) =>
        users.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const usersCount = computed(() => props.users.data.length);

const createProxyUrl = (url) => {
    // Remove any protocol and domain part to get just the path
    if (url.includes('amazonaws.com')) {
        // Create a proxy URL that your server can handle
        return `/image-proxy?url=${encodeURIComponent(url)}`;
    }
    return url;
};

const asset = (path) => {
  const cleanPath = path.startsWith('/') ? path.substring(1) : path;
  return `/${cleanPath}`;
};

const profileImageUrl = (profileImage) => {
    if (!profileImage) return "/default-profile.png";
    
    // For S3 URLs
    if (profileImage.includes('s3.') || profileImage.includes('amazonaws.com')) {
        // Extract just the S3 URL part using regex
        const s3UrlMatch = profileImage.match(/(https?:\/\/.*amazonaws\.com\/.*)/);
        if (s3UrlMatch && s3UrlMatch[1]) {
            // Use the full URL directly without any modification
            // This is a direct approach that bypasses Vue's URL handling
            return s3UrlMatch[1];
        }
    }
    
    // For other URLs
    if (profileImage.includes("http://") || profileImage.includes("https://")) {
        return profileImage;
    }
    
    // For relative paths
    return asset(profileImage);
};

// const getThumbnailUrl = (thumbnail) => {
//     return `${thumbnail}`;
// };

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
