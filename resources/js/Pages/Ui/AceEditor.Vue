<template>
	<div ref="editor" :style="{ width, height }"></div>
</template>

<script>
import ace from 'ace-builds';
import 'ace-builds/webpack-resolver';

export default {
	name: 'AceEditor',
	props: {
		modelValue: {
			type: String,
			required: true,
		},
		width: {
			type: String,
			default: '100%',
		},
		height: {
			type: String,
			default: '400px',
		},
		language: {
			type: String,
			default: 'javascript',
		},
		theme: {
			type: String,
			default: 'monokai',
		},
	},
	emits: ['update:modelValue'],
	watch: {
		modelValue(newValue) {
			if (this.editor.getValue() !== newValue) {
				this.editor.setValue(newValue, 1);
			}
		},
	},
	mounted() {
		this.editor = ace.edit(this.$refs.editor);
		this.editor.session.setMode(`ace/mode/${this.language}`);
		this.editor.setTheme(`ace/theme/${this.theme}`);
		this.editor.setValue(this.modelValue, 1);

		this.editor.session.on('change', () => {
			const content = this.editor.getValue();
			if (content !== this.modelValue) {
				this.$emit('update:modelValue', content);
			}
		});
	},
	beforeUnmount() {
		this.editor.destroy();
	},
};
</script>

<style scoped>
div {
	border: 1px solid #e0e0e0;
}
</style>
