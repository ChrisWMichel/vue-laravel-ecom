<script setup>
import userLayout from "@/Layouts/userLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head } from "@inertiajs/vue3";
import AddressInfo from "./Partials/AddressInfo.vue";
import { defineProps, onMounted } from "vue";
import UpdateImage from "./Partials/UpdateImage.vue";
import Swal from "sweetalert2";

const props = defineProps({
    status: {
        type: String,
    },
    flash: {
        type: Object,
    },
});

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
    <Head title="Profile" />

    <userLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Profile
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
                <div class="p-4 bg-white shadow sm:rounded-lg sm:p-8">
                    <AddressInfo :status="status" class="max-w-xl" />
                </div>
                <div class="p-4 bg-white shadow sm:rounded-lg sm:p-8">
                    <UpdateImage class="max-w-xl" />
                </div>
                <div class="p-4 bg-white shadow sm:rounded-lg sm:p-8">
                    <UpdateProfileInformationForm
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="p-4 bg-white shadow sm:rounded-lg sm:p-8">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="p-4 bg-white shadow sm:rounded-lg sm:p-8">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </userLayout>
</template>
