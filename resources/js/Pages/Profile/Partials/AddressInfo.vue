<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Address Information
            </h2>

            <p class="mt-1 text-sm text-gray-600">Update your address.</p>
        </header>

        <form
            @submit.prevent="submitForm"
            enctype="multipart/form-data"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="address" value="Address" />

                <input
                    id="address"
                    type="text"
                    class="block w-full mt-1"
                    v-model="form.address"
                    required
                    autofocus
                    autocomplete="address"
                />

                <InputError class="mt-2" :message="form.errors.address" />
            </div>

            <div>
                <InputLabel for="city" value="City, State" />

                <input
                    id="city"
                    type="text"
                    class="block w-full mt-1"
                    v-model="form.city"
                    required
                    autocomplete="city"
                />

                <InputError class="mt-2" :message="form.errors.city" />
            </div>
            <div>
                <InputLabel for="country" value="Country" />

                <input
                    id="country"
                    type="text"
                    class="block w-full mt-1"
                    v-model="form.country"
                    required
                    autocomplete="country"
                />

                <InputError class="mt-2" :message="form.errors.country" />
            </div>
            <div>
                <InputLabel for="zip_code" value="Zip Code" />

                <input
                    id="zip_code"
                    type="text"
                    class="block w-full mt-1"
                    v-model="form.zip_code"
                    required
                    autocomplete="postal_code"
                />

                <InputError class="mt-2" :message="form.errors.zip_code" />
            </div>
            <div>
                <InputLabel for="phone_number" value="Phone Number" />

                <input
                    id="phone_number"
                    type="text"
                    class="block w-full mt-1"
                    v-model="form.phone_number"
                    required
                    autocomplete="phone"
                />

                <InputError class="mt-2" :message="form.errors.phone_number" />
            </div>

            <div>
                <InputLabel for="profile_image" value="Profile Image" />

                <input
                    @change="handleProfileChange"
                    id="profile_image"
                    type="file"
                    class="block w-full mt-1"
                />

                <InputError class="mt-2" :message="form.errors.profile_image" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>

<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";

const user = usePage().props.auth.user;
const toast = useToast();

const form = useForm({
    address: user.address || "",
    city: user.city || "",
    country: user.country || "",
    zip_code: user.zip_code || "",
    phone_number: user.phone_number || "",
    profile_image: null,
});

const handleProfileChange = (event) => {
    form.profile_image = event.target.files[0] || null;
};

const submitForm = async () => {
    form.post(route("update.address"), {
        forceFormData: true,
        onSuccess: () => {
            toast.success("Address updated successfully.");
        },
    });
};
</script>
