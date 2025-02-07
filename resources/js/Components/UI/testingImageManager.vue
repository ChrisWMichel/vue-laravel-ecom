<template>
    <div>
        <button @click="testDeleteImage">Test Delete Image</button>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { route } from "ziggy-js";

const product = ref({ id: 3 }); // Replace with actual product ID
const csrfToken = ref(
    document.querySelector('meta[name="csrf-token"]').getAttribute("content")
);

const testDeleteImage = () => {
    const url = route("admin.images.delete", {
        product: product.value.id,
        imageField: "thumbnail",
    });
    console.log(`Generated URL: ${url}`);
    axios
        .delete(url, {
            headers: {
                "X-CSRF-TOKEN": csrfToken.value,
            },
        })
        .then((response) => {
            console.log(`Response status: ${response.status}`);
            console.log(`Image deleted successfully`);
        })
        .catch((error) => {
            console.error(`Error deleting image:`, error);
        });
};
</script>
