<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Image</h2>

            <p class="mt-1 text-sm text-gray-600">Add or Update your image.</p>
        </header>
        <div class="flex content-center gap-4 mt-4">
            <div>
                <img
                    v-if="user.profile_image"
                    :src="user.profile_image"
                    alt="Profile Image"
                    class="w-24 h-24 mx-auto rounded-full"
                />
            </div>
            <div>
                <form enctype="multipart/form-data" class="mt-6 space-y-6">
                    <div>
                        <InputLabel for="profile_image" value="Profile Image" />

                        <input
                            @change="handleProfileChange"
                            id="profile_image"
                            type="file"
                            class="block w-full mt-1"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.profile_image"
                        />
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";

const user = usePage().props.auth.user;
const toast = useToast();

const form = useForm({
    profile_image: user.profile_image || null,
});

const handleProfileChange = (event) => {
    form.profile_image = event.target.files[0] || null;
    form.post(route("update.profile.image"), {
        forceFormData: true,
        onSuccess: () => {
            toast.success("Image updated successfully.");
        },
    });
};
</script>

<style scoped>
.flex-col {
    display: flex;
    flex-direction: column;
    align-items: center;
}
</style>
