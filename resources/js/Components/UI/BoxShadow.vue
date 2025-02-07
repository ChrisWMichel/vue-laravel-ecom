<template>
    <div
        class="p-4 text-gray-300 border border-gray-200 rounded-md shadow-md dark:border-gray-700 dark:bg-gray-300"
    >
        <div class="mb-1 font-medium text-gray-900">
            <slot name="header"></slot>
        </div>
        <slot></slot>
    </div>
</template>

<script lang="ts" setup></script>

<style></style>
