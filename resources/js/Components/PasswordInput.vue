<script setup>
import { onMounted, ref } from 'vue';
import {
	EyeSlashIcon,
	EyeIcon
} from '@heroicons/vue/24/outline'

defineProps({
	modelValue: {
		type: String,
		required: true,
	},
});

// Use defineOptions to set component options like inheritAttrs
defineOptions({
	inheritAttrs: false,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
	if (input.value.hasAttribute('autofocus')) {
		input.value.focus();
	}
});
defineExpose({ focus: () => input.value.focus() });
const visible = ref(false)
</script>

<template>
	<div class="relative" >
		<input v-bind="$attrs"
		class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
		:value="modelValue"
		:type="visible?'text':'password'"
		@input="$emit('update:modelValue', $event.target.value)"
		ref="input" />
		<button class="absolute right-2 top-2" @click.prevent="visible = !visible">
			<EyeSlashIcon v-if="!visible" class="w-6 h-6" ></EyeSlashIcon>
			<EyeIcon v-if="visible"    class="w-6 h-6" ></EyeIcon>
		</button>
	</div>
</template>