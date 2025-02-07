<template>
    <div class="flex items-center justify-center w-full h-full">
        <div class="w-3/4 p-6 m-6 bg-white border border-gray-400 rounded-lg">
            <Menubar v-if="editor" :editor="editor" />
            <editor-content v-if="editor" :editor="editor" />
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, onBeforeUnmount, watch } from "vue";
import { Editor, EditorContent } from "@tiptap/vue-3";
import { mergeAttributes } from "@tiptap/core";
import { Heading as TipTapHeading } from "@tiptap/extension-heading";
import Bold from "@tiptap/extension-bold";
import Italic from "@tiptap/extension-italic";
import Underline from "@tiptap/extension-underline";
import Strike from "@tiptap/extension-strike";
import BulletList from "@tiptap/extension-bullet-list";
import OrderedList from "@tiptap/extension-ordered-list";
import ListItem from "@tiptap/extension-list-item";
import Paragraph from "@tiptap/extension-paragraph";
import Text from "@tiptap/extension-text";
import Document from "@tiptap/extension-document";
import Image from "@tiptap/extension-image";
import { Color } from "@tiptap/extension-color";
import TextStyle from "@tiptap/extension-text-style";
import Menubar from "@/Components/Menubar.vue";

const Heading = TipTapHeading.extend({
    renderHTML({ node, HTMLAttributes }) {
        const hasLevel = this.options.levels.includes(node.attrs.level);
        const level = hasLevel ? node.attrs.level : this.options.levels[0];

        return [
            `h${level}`,
            mergeAttributes(this.options.HTMLAttributes, HTMLAttributes, {
                class: `h${node.attrs.level}-style`,
            }),
            0,
        ];
    },
});

const props = defineProps({
    modelValue: String,
});

const emit = defineEmits(["update:modelValue"]);

const editor = ref(null);

onMounted(() => {
    editor.value = new Editor({
        extensions: [
            Document,
            Paragraph,
            Text,
            Bold,
            Italic,
            Underline,
            Strike,
            BulletList,
            OrderedList,
            ListItem,
            Image,
            TextStyle,
            Color,
            Heading,
        ],
        content: props.modelValue,
        onUpdate: ({ editor }) => {
            emit("update:modelValue", editor.getHTML());
        },
        editorProps: {
            attributes: {
                class: "prose max-w-none",
            },
        },
    });
});

watch(
    () => props.modelValue,
    (newValue) => {
        if (editor.value && editor.value.getHTML() !== newValue) {
            editor.value.commands.setContent(newValue, false);
        }
    }
);

onBeforeUnmount(() => {
    if (editor.value) {
        editor.value.destroy();
    }
});
</script>

<style>
.tiptap.ProseMirror.prose.max-w-none {
    padding: 15px;
    max-width: 100%;
    border: black 1px solid;
}
.h1-style {
    font-size: 2em;
    font-weight: bold;
}

.h2-style {
    font-size: 1.75em;
    font-weight: bold;
}

.h3-style {
    font-size: 1.5em;
    font-weight: bold;
}

.h4-style {
    font-size: 1.25em;
    font-weight: bold;
}

.h5-style {
    font-size: 1em;
    font-weight: bold;
}

.h6-style {
    font-size: 0.875em;
    font-weight: bold;
}
</style>
