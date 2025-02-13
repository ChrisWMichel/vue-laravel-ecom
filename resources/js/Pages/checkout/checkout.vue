<template>
    <Head title="Checkout" />
    <userLayout>
        <div
            v-if="user && user.address"
            class="container items-center mx-auto my-10"
        >
            <div class="w-1/4 mx-auto">
                <Disclosure>
                    <DisclosureButton
                        class="p-2 text-2xl font-bold bg-gray-300 rounded-md"
                    >
                        <div class="flex items-center justify-between">
                            Current Address
                            <ArrowDownCircleIcon class="w-6 h-6" />
                        </div>
                    </DisclosureButton>
                    <DisclosurePanel class="p-4 bg-gray-300 rounded-lg">
                        <AddressInfo :removeBtn="true" />
                    </DisclosurePanel>
                </Disclosure>
            </div>
            <div class="flex flex-col justify-between w-full mt-4 md:flex-row">
                <div class="w-full md:w-3/4">
                    <checkoutItems />
                </div>
                <div
                    class="flex flex-col justify-center w-full mt-4 md:w-1/4 md:mt-0 md:justify-start"
                >
                    <div class="mb-4"><coupon /></div>
                    <div><Stripe /></div>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="flex items-center justify-center h-96">
                <Link
                    :href="route('profile.edit')"
                    class="text-2xl text-indigo-600 hover:underline"
                    >Please add an address to your profile.</Link
                >
            </div>
        </div>
    </userLayout>
</template>

<script setup>
import { useCartStore } from "@/stores/useCartStore";
import { useToast } from "vue-toastification";
import { router, Head, usePage, Link } from "@inertiajs/vue3";
import AddressInfo from "../Profile/Partials/AddressInfo.vue";
import userLayout from "@/Layouts/userLayout.vue";
import { onMounted } from "vue";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ArrowDownCircleIcon } from "@heroicons/vue/24/outline";
import checkoutItems from "../checkout/checkoutItems.vue";
import Stripe from "@/Pages/payments/Strip.vue";
import coupon from "@/Pages/Coupon/coupon.vue";

const cartStore = useCartStore();
const toast = useToast();
const user = usePage().props.auth.user;

onMounted(() => {
    if (cartStore.cartItems.length <= 0 || user == null) {
        toast.error("Your cart is empty or you are not logged in");
        router.get(route("home"));
    }
});
</script>

<style scoped></style>
