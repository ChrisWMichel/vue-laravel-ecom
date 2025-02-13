<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Address Information
            </h2>
            <Link
                v-if="removeBtn"
                :href="route('profile.edit')"
                class="text-sm text-indigo-600 hover:underline"
                >Update your address.</Link
            >
            <p v-else class="mt-1 text-sm text-gray-600">
                Update your address.
            </p>
        </header>

        <form @submit.prevent="submitForm" class="mt-6 space-y-6">
            <div>
                <InputLabel for="address" value="Address" />

                <TextInput
                    id="address"
                    type="text"
                    class="block w-full mt-1"
                    v-model="form.address"
                    required
                    autocomplete="address"
                    :disabled="removeBtn"
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
                    :disabled="removeBtn"
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
                    :disabled="removeBtn"
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
                    :disabled="removeBtn"
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
                    :disabled="removeBtn"
                />

                <InputError class="mt-2" :message="form.errors.phone_number" />
            </div>

            <div class="flex items-center gap-4" v-if="!removeBtn">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
            </div>
        </form>
    </section>
</template>

<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm, usePage, Link } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import TextInput from "@/Components/TextInput.vue";

const user = usePage().props.auth.user;
const toast = useToast();
defineProps({
    removeBtn: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const form = useForm({
    address: user.address || "",
    city: user.city || "",
    country: user.country || "",
    zip_code: user.zip_code || "",
    phone_number: user.phone_number || "",
    profile_image: null,
});

const submitForm = async () => {
    form.post(route("update.address"), {
        forceFormData: true,
        onSuccess: () => {
            toast.success("Address updated successfully.");
        },
    });
};
</script>
