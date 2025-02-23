import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { useToast } from "vue-toastification";

export const useFavoriteStore = defineStore("favorite", () => {
    const favoriteItems = ref(
        JSON.parse(localStorage.getItem("favoriteItems")) || []
    );
    const toast = useToast();

    const addToFavorite = (product) => {
        favoriteItems.value.push(product);
        localStorage.setItem(
            "favoriteItems",
            JSON.stringify(favoriteItems.value)
        );
        toast.success("Added to favorites");
    };

    const removeFromFavorite = (product) => {
        favoriteItems.value = favoriteItems.value.filter(
            (item) => item.id !== product.id
        );
        localStorage.setItem(
            "favoriteItems",
            JSON.stringify(favoriteItems.value)
        );
        toast.success("Removed from favorites");
    };

    const isFavorite = (product) => {
        return computed(() =>
            favoriteItems.value.some((item) => item.id === product.id)
        );
    };

    return {
        favoriteItems,
        addToFavorite,
        removeFromFavorite,
        isFavorite,
    };
});
