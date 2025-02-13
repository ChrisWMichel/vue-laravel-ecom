<script setup>
import userLayout from "@/Layouts/userLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head } from "@inertiajs/vue3";
import AddressInfo from "./Partials/AddressInfo.vue";
import { defineProps, onMounted, ref } from "vue";
import UpdateImage from "./Partials/UpdateImage.vue";
import Swal from "sweetalert2";
import orderHistory from "./orderHistory.vue";

const props = defineProps({
    status: {
        type: String,
    },
    flash: {
        type: Object,
    },
});
const profile = ref(true);
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

<template>
    <userLayout>
        <div
            class="container px-6 py-6 mx-auto my-4 border-t border-b border-gray-200 sm:px-6 lg:px-8"
        >
            <div class="flex items-center space-x-4">
                <button
                    type="button"
                    class="text-2xl font-semibold leading-tight text-gray-800 hover:text-fuchsia-300"
                    @click="profile = true"
                    :class="
                        profile
                            ? 'text-fuchsia-700 font-extrabold'
                            : 'text-gray-500'
                    "
                >
                    Profile
                </button>
                <button
                    type="button"
                    @click="profile = false"
                    :class="
                        !profile
                            ? 'text-fuchsia-700 font-extrabold'
                            : 'text-gray-500'
                    "
                    class="text-2xl font-semibold leading-tight text-gray-800 hover:text-fuchsia-300"
                >
                    Order History
                </button>
            </div>
        </div>

        <div v-if="profile">
            <Head title="Profile" />
            <div class="py-12">
                <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
                    <div class="p-4 bg-white shadow sm:rounded-lg sm:p-8">
                        <UpdateProfileInformationForm
                            :status="status"
                            class="max-w-xl"
                        />
                    </div>
                    <div class="p-4 bg-white shadow sm:rounded-lg sm:p-8">
                        <AddressInfo :status="status" class="max-w-xl" />
                    </div>
                    <div class="p-4 bg-white shadow sm:rounded-lg sm:p-8">
                        <UpdateImage class="max-w-xl" />
                    </div>

                    <div class="p-4 bg-white shadow sm:rounded-lg sm:p-8">
                        <UpdatePasswordForm class="max-w-xl" />
                    </div>

                    <div class="p-4 bg-white shadow sm:rounded-lg sm:p-8">
                        <DeleteUserForm class="max-w-xl" />
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="py-12">
            <Head title="History" />
            <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
                <div class="p-4 bg-white shadow sm:rounded-lg sm:p-8">
                    <orderHistory class="max-w-xl" />
                </div>
            </div>
        </div>
    </userLayout>
</template>
