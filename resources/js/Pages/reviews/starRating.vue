<template>
  <div class="star-rating">
    <div
      v-for="i in maxStars"
      :key="i"
      @click="setRating(i)"
      @mouseover="hoverRating(i)"
      @mouseleave="resetHover"
      :class="['star', i <= (isHovered ? hoverValue : rating) ? 'filled' : '', width]"
      :style="{ fontSize: `${fontSize}px` }"
    >
      ★
    </div>
  </div>
</template>

<script>
import { ref, watch } from "vue";

export default {
  props: {
    value: {
      type: Number,
      default: 0,
    },
    maxStars: {
      type: Number,
      default: 5,
    },
    fontSize: {
      type: Number,
      default: 34, // Default font size
    },
    width: {
      type: String,
      default: "w-10",
    },
  },
  setup(props, { emit }) {
    //const rating = ref(props.value);
    const isHovered = ref(false);
    const hoverValue = ref(0);
    const rating = ref(props.rating || props.value || 0);

    watch(
      () => props.rating,
      (newRating) => {
        if (newRating !== undefined) {
          rating.value = newRating;
        }
      },
      { immediate: true }
    );

    watch(
      () => props.value,
      (newValue) => {
        if (newValue !== undefined && props.rating === undefined) {
          rating.value = newValue;
        }
      },
      { immediate: true }
    );

    const setRating = (newRating) => {
      rating.value = newRating;
      emit("ratingData", newRating);
    };

    const hoverRating = (value) => {
      if (isHovered.value) {
        hoverValue.value = value;
      }
    };

    const resetHover = () => {
      hoverValue.value = 0;
    };

    return {
      rating,
      isHovered,
      hoverRating,
      resetHover,
      setRating,
    };
  },
};
</script>

<style scoped>
.star-rating {
  display: inline-block;
}

.star {
  display: inline-block;
  font-size: 34px;
  cursor: pointer;
  margin: 2px;
  color: rgb(222, 222, 222);
}

.filled {
  color: gold;
}
</style>
