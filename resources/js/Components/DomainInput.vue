<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
	modelValue: {
		type: String,
		required: true,
	},
	host: String
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
	if (input.value.hasAttribute('autofocus')) {
		input.value.focus();
	}
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
	<div class="relative ">
		<input type="text"
			v-bind="props"
			class="border-gray-300  w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
			:value="modelValue"
			@input="$emit('update:modelValue', $event.target.value)"
			ref="input" />
		<!-- <input  class="block w-full rounded-md border-0 py-1.5 pl-7 pr-12 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="0.00" aria-describedby="price-currency" /> -->
		<div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
			<span class="text-gray-500 sm:text-sm" id="price-currency">.{{host}}</span>
		</div>
	</div>
</template>
