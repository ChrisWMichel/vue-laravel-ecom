import { defineStore } from "pinia";
import { ref } from "vue";

export const useImageStore = defineStore("imageStore", () => {
    const selectedImageField = ref("thumbnail");

    const setSelectedImageField = (field) => {
        selectedImageField.value = field;
    };

    const determineEmptyImageField = (product) => {
        if (product.first_image === null) {
            setSelectedImageField("first_image");
        } else if (product.second_image === null) {
            setSelectedImageField("second_image");
        } else if (product.third_image === null) {
            setSelectedImageField("third_image");
        } else {
            setSelectedImageField("first_image");
        }
    };

    return {
        selectedImageField,
        determineEmptyImageField,
    };
});
