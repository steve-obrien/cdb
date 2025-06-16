<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Props:
///////////////////////////////////////////////////////////////////////////////
const props = defineProps({
	auth: { type: Object },
	email: { type: Array },
});


</script>

<template>

	<Head title="Email" />

	<AuthenticatedLayout class="bg-gray-100 dark:bg-black">
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Team</h2>
		</template>

		<div class="p-4 md:p-8">
			WEFEWFEWF {{ emails }}
		</div>
		

	</AuthenticatedLayout>

</template>
