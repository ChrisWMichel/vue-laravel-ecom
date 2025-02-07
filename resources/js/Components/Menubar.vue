<template>
    <div class="flex justify-center gap-1 menu-bar">
        <button
            type="button"
            @click="editor.chain().focus().toggleBold().run()"
            :class="{ 'is-active': editor.isActive('bold') }"
        >
            Bold
        </button>
        <button
            type="button"
            @click="editor.chain().focus().toggleItalic().run()"
            :class="{ 'is-active': editor.isActive('italic') }"
        >
            Italic
        </button>
        <button
            type="button"
            @click="editor.chain().focus().toggleUnderline().run()"
            :class="{ 'is-active': editor.isActive('underline') }"
        >
            Underline
        </button>
        <button
            type="button"
            @click="editor.chain().focus().toggleStrike().run()"
            :class="{ 'is-active': editor.isActive('strike') }"
        >
            Strike
        </button>
        <button
            type="button"
            @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
            :class="{ 'is-active': editor.isActive('heading', { level: 1 }) }"
        >
            H1
        </button>
        <button
            type="button"
            @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
            :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }"
        >
            H2
        </button>
        <button
            type="button"
            @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
            :class="{ 'is-active': editor.isActive('heading', { level: 3 }) }"
        >
            H3
        </button>
        <button
            type="button"
            @click="editor.chain().focus().toggleBulletList().run()"
            :class="{ 'is-active': editor.isActive('bulletList') }"
        >
            Bullet List
        </button>
        <input type="color" @input="setColor" />
        <button @click="triggerFileInput">Image</button>
        <input
            type="file"
            ref="fileInput"
            @change="addImage"
            style="display: none"
        />
    </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
    editor: {
        type: Object,
        required: true,
    },
});

const fileInput = ref(null);

const triggerFileInput = () => {
    fileInput.value.click();
};

const addImage = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const url = e.target.result;
            props.editor.chain().focus().setImage({ src: url }).run();
        };
        reader.readAsDataURL(file);
    }
};

const setColor = (event) => {
    const color = event.target.value;
    props.editor.chain().focus().setColor(color).run();
};
</script>

<style scoped>
.menu-bar {
    margin-bottom: 1rem;
}

.menu-bar button {
    margin-right: 0.5rem;
    padding: 0.5rem 1rem;
    border: none;
    background: #f0f0f0;
    cursor: pointer;
}

.menu-bar button.is-active {
    background: #d0d0d0;
}
</style>
