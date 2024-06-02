<template>
	<div ref="editorContainer" class="monaco-editor-container"></div>
</template>

<script>
import * as monaco from 'monaco-editor';
import { defineComponent } from 'vue';


export default defineComponent({
	name: 'MonacoEditor',
	props: {
		modelValue: {
			type: String,
			default: '',
		},
	},
	emits: ['update:modelValue'],
	data() {
		return {
			editor: null,
		};
	},
	watch: {
		modelValue(newValue) {
			if (this.editor && newValue !== this.editor.getValue()) {
				this.editor.setValue(newValue);
			}
		},
	},
	mounted() {
		this.editor = monaco.editor.create(this.$refs.editorContainer, {
			value: this.modelValue,
			language: 'html',
			automaticLayout: true,
			minimap: {
				enabled: false,
			},
			overviewRulerLanes: 0,
		});

		this.editor.onDidChangeModelContent(() => {
			const value = this.editor.getValue();
			this.$emit('update:modelValue', value);
		});
	},
	beforeUnmount() {
		if (this.editor) {
			this.editor.dispose();
		}
	},
});
</script>

<style scoped>
.monaco-editor-container {
	width: 100%;
	height: 100%;
}
</style>