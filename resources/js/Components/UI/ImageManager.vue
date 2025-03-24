<template>
  <div>
    <BoxShadow class="p-4">
      <div v-if="imageField == 'thumbnail'" class="text-2xl font-semibold text-gray-500">
        Upload Thumbnail
      </div>
      <div v-else class="text-2xl font-semibold text-gray-500">Upload Images</div>
      <form @submit.prevent="handleFileChange">
        <input type="hidden" name="_token" :value="csrfToken" />

        <section class="flex items-center mt-4 space-x-4">
          <input
            @change="handleFileChange"
            class="font-semibold text-black border border-gray-200 rounded-md file:px-4 file:py-2 file:border-0 file:bg-gray-100 file:rounded-md file:cursor-pointer file:hover:bg-gray-200 file:hover:cursor-pointer"
            type="file"
          />
          <button type="reset" @click="reset" class="text-gray-600 btn-outline">
            Reset
          </button>
        </section>
        <div v-if="imageErrors.length > 0" class="mt-2 text-red-500 input-error">
          <div v-for="(error, index) in imageErrors" :key="index">
            {{ error }}
          </div>
        </div>
      </form>
    </BoxShadow>

    <BoxShadow class="p-4 mt-4">
      <div v-if="imageField == 'thumbnail'" class="text-gray-800">Current Thumbnail</div>
      <div v-else class="text-gray-800">Current Images</div>
      <section class="flex items-center mt-4 space-x-4">
        <div
          v-if="imageField === 'thumbnail'"
          class="relative flex flex-col justify-between"
        >
          <img
            :src="isAbsoluteUrl(product.thumbnail) ? product.thumbnail : ''"
            alt="Thumbnail Image"
            class="object-cover w-full h-40 rounded-md"
          />
        </div>
        <div v-else class="relative flex flex-row justify-between">
          <div v-if="product.first_image" class="relative flex-1">
            <img
              :src="isAbsoluteUrl(product.first_image) ? product.first_image : ''"
              alt="Product Image"
              class="object-cover w-full h-40 rounded-md"
            />
            <button
              @click.prevent="deleteImage('first_image')"
              class="absolute p-1 text-white bg-red-500 rounded-md top-2 right-2"
            >
              X
            </button>
          </div>
          <div v-if="product.second_image" class="relative flex-1 ml-4">
            <img
              :src="isAbsoluteUrl(product.second_image) ? product.second_image : ''"
              alt="Product Image"
              class="object-cover w-full h-40 rounded-md"
            />
            <button
              @click.prevent="deleteImage('second_image')"
              class="absolute p-1 text-white bg-red-500 rounded-md top-2 right-2"
            >
              X
            </button>
          </div>
          <div v-if="product.third_image" class="relative flex-1 ml-4">
            <img
              :src="isAbsoluteUrl(product.third_image) ? product.third_image : ''"
              alt="Product Image"
              class="object-cover w-full h-40 rounded-md"
            />
            <button
              @click.prevent="deleteImage('third_image')"
              class="absolute p-1 text-white bg-red-500 rounded-md top-2 right-2"
            >
              X
            </button>
          </div>
        </div>
      </section>
    </BoxShadow>
  </div>
</template>

<script setup>
import BoxShadow from "@/Components/UI/BoxShadow.vue";
import { useForm, usePage, router } from "@inertiajs/vue3";
import { ref, computed, watch, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useImageStore } from "@/stores/imageStore";
import NProgress from "nprogress";
//import { route } from "ziggy-js";
import Swal from "sweetalert2";

const props = defineProps({
  product: {
    type: Object,
  },
  imageField: {
    type: String,
  },
  disableButton: {
    type: Boolean,
    default: false,
  },
});

const isAbsoluteUrl = (url) => {
  if (!url) return false;
  return url.startsWith("http://") || url.startsWith("https://");
};
const isButtonDisabled = ref(props.disableButton);

Inertia.on("progress", (event) => {
  if (event.detail.progress.percentage) {
    NProgress.set((event.detail.progress.percentage / 100) * 0.9);
  }
});
const page = usePage();

const csrfToken = computed(() => page.props.csrf);
const form = useForm({
  images: [],
});

const imageStore = useImageStore();
const selectedImageField = ref(props.imageField);

const handleFileChange = (event) => {
  const files = event.target.files;
  if (props.imageField === "first_image") {
    imageStore.determineEmptyImageField(props.product);
    selectedImageField.value = imageStore.selectedImageField;
  }

  if (files && files.length > 0) {
    const file = files[0];
    const formData = new FormData();
    formData.append(selectedImageField.value, file);
    router.post(
      route("admin.images.update", {
        product: props.product,
        imageField: selectedImageField.value,
      }),
      formData,
      {
        onFinish: () => {
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: `${selectedImageField.value} updated successfully`,
            showConfirmButton: false,
            timer: 2000,
          });
        },
        onError: (error) => {
          Swal.fire({
            position: "top-end",
            icon: "error",
            title: `Error updating ${selectedImageField.value}:`,
            showConfirmButton: false,
            timer: 2000,
          });
        },
      }
    );
  } else {
    console.error("No files selected");
  }
};

const deleteImage = (imageField) => {
  const url = route("admin.images.delete", {
    product: props.product,
    imageField,
  });
  router.delete(url, {
    onSuccess: (response) => {
      // console.log(`Response status: ${response.status}`);
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: `${imageField} deleted successfully`,
        showConfirmButton: false,
        timer: 2000,
      });
      props.product[imageField] = null;
      checkButtonState();
    },
    onError: (error) => {
      console.error(`Error deleting ${imageField}:`, error);
    },
  });
};

const checkButtonState = () => {
  if (props.imageField === "thumbnail") {
    isButtonDisabled.value = !!props.product.thumbnail;
  } else {
    isButtonDisabled.value =
      !!props.product.first_image &&
      !!props.product.second_image &&
      !!props.product.third_image;
  }
};

watch(() => props.product, checkButtonState, { deep: true });

const getImageUrl = (path) => {
  return path ? `${window.location.origin}/${path}` : "";
};
const canUpload = computed(() => form.images.length > 0);

const imageErrors = computed(() => {
  return Object.values(form.errors);
});
const reset = () => {
  form.reset();
};
</script>

<style></style>
