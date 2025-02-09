import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { useToast } from "vue-toastification";
//import axios from "axios";
//import { BASE_URL } from "../helpers/config";

export const useCartStore = defineStore("cart", () => {
    const cartItems = ref(JSON.parse(localStorage.getItem("cartItems")) || []);
    const totalPrice = ref(0);
    const toast = useToast();

    const addToCart = (product) => {
        console.log("cartStore - cartItems:", cartItems.value);
        console.log("cartStore - product:", product);
        const item = cartItems.value.find(
            (item) =>
                item.product_id === product.product_id &&
                item.size === product.size &&
                item.color === product.color
        );
        const totalQty = item ? product.qty + item.qty : product.qty;
        if (totalQty > product.inStock) {
            toast(
                `Quantity exceeds the available stock (${product.inStock}).`,
                {
                    type: "notice",
                }
            );
            return {
                success: false,
                message: "Quantity exceeds the available stock.",
            };
        }

        if (item) {
            item.qty += product.qty;
            localStorage.setItem("cartItems", JSON.stringify(cartItems.value));
            toast(
                `Item already in cart. Quantity increased by ${product.qty}`,
                {
                    type: "notice",
                }
            );
            return {
                success: true,
                message: "Item already in cart. Quantity increased.",
            };
        } else {
            cartItems.value.push({ ...product });
            toast("Item added to cart.", {
                type: "success",
            });
        }
        localStorage.setItem("cartItems", JSON.stringify(cartItems.value));
        return { success: true, message: "Item added to cart." };
    };

    const removeFromCart = (item) => {
        cartItems.value = cartItems.value.filter(
            (product) => product.ref !== item.ref
        );
        toast("Item removed from cart.", {
            type: "success",
        });

        localStorage.setItem("cartItems", JSON.stringify(cartItems.value));
    };

    const clearCartItems = () => {
        cartItems.value = [];
        localStorage.removeItem("cartItems");
    };

    const changeQty = (product, quantity) => {
        const item = cartItems.value.find(
            (item) =>
                item.product_id === product.product_id &&
                item.size === product.size &&
                item.color === product.color
        );

        if (!item) {
            toast("Item not found in cart.", {
                type: "error",
            });
            return {
                success: false,
                message: "Item not found in cart.",
            };
        }

        if (quantity > product.inStock) {
            toast("Quantity exceeds the available stock.", {
                type: "error",
            });
            return {
                success: false,
                message: "Quantity exceeds the available stock.",
            };
        }

        if (quantity > item.qty) {
            // Increase quantity
            const increaseBy = quantity - item.qty;
            item.qty += increaseBy;
            toast(`Item quantity increased by ${increaseBy}.`, {
                type: "success",
            });
        } else if (quantity < item.qty) {
            // Decrease quantity
            const decreaseBy = item.qty - quantity;
            item.qty -= decreaseBy;
            toast(`Item quantity decreased by ${decreaseBy}.`, {
                type: "success",
            });
        }

        localStorage.setItem("cartItems", JSON.stringify(cartItems.value));
        return {
            success: true,
            message: "Item quantity changed.",
        };
    };

    const getTotalPrice = computed(() => {
        return cartItems.value.reduce((total, item) => {
            return total + item.price * item.qty;
        }, 0);
    });

    const getTotalProducts = computed(() => {
        return cartItems.value.length;
    });

    const getTotalQuantity = computed(() => {
        return cartItems.value.reduce((total, item) => {
            return total + item.qty;
        }, 0);
    });

    return {
        cartItems,
        addToCart,
        changeQty,
        removeFromCart,
        clearCartItems,
        getTotalPrice,
        getTotalProducts,
        getTotalQuantity,
    };
});
