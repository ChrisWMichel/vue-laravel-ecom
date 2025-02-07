import { defineStore } from "pinia";
import { ref } from "vue";
import { useToast } from "vue-toastification";
//import axios from "axios";
//import { BASE_URL } from "../helpers/config";

export const useCartStore = defineStore("cart", () => {
    const cartItems = ref(JSON.parse(localStorage.getItem("cartItems")) || []);
    const toast = useToast();

    console.log("cartItems:", cartItems.value);

    const addToCart = (product) => {
        const item = cartItems.value.find(
            (item) =>
                item.product_id === product.product_id &&
                item.size === product.size &&
                item.color === product.color
        );

        if (item) {
            toast("Item already in cart. We increased the quantity by one.", {
                type: "info",
            });
            item.qty += 1;
        } else {
            cartItems.value.push({ ...product });
            toast("Item added to cart.", {
                type: "success",
            });
        }
        localStorage.setItem("cartItems", JSON.stringify(cartItems.value));
    };

    const removeFromCart = (product) => {
        const index = cartItems.value.findIndex(
            (item) =>
                item.product_id === product.product_id &&
                item.size === product.size &&
                item.color === product.color
        );

        if (index !== -1) {
            cartItems.value.splice(index, 1);
            toast("Item removed from cart.", {
                type: "success",
            });
        }

        localStorage.setItem("cartItems", JSON.stringify(cartItems.value));
    };

    const incrementQty = (product) => {
        const item = cartItems.value.find(
            (item) =>
                item.product_id === product.product_id &&
                item.size === product.size &&
                item.color === product.color
        );

        if (item) {
            item.qty += 1;
            toast("Item quantity increased.", {
                type: "success",
            });
        }

        localStorage.setItem("cartItems", JSON.stringify(cartItems.value));
    };

    return {
        cartItems,
        addToCart,
        incrementQty,
        removeFromCart
    };
});
