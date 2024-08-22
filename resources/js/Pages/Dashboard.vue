
<template>
	<Head title="Dashboard" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
		</template>

		<div class="p-4 md:p-8">
			<div class="mt-8 flow-root">
				<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
					<div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
						<Link :href="route('chat')" class="w-64 h-52 border items-center border-gray-300 hover:border-pink-700 border-dashed rounded-md p-6 flex flex-col ">
							<div>
								<div class="text-7xl text-center w-full">🤖</div>
								<div class="text-lg text-center w-full">Chat to Ni AI</div>
							</div><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon" class="h-6 w-6 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"></path></svg>
						</Link>
					</div>
				</div>
			</div>
		</div>
		<div class="p-4 md:p-8">
			<div class="sm:flex sm:items-center">
				<div class="sm:flex-auto">
					<h1 class="text-base font-semibold leading-6 text-gray-900">Databases</h1>
					<p class="mt-2 text-sm text-gray-700">Lets make new databases for shits and giggles.</p>
				</div>
			</div>

			<div class="mt-8 flow-root">
				<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
					<div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
						<button @click="newdb" class="w-64 h-52 border items-center border-gray-300 hover:border-pink-700 border-dashed rounded-md p-6 flex flex-col ">
							<div>
								<div class="text-7xl text-center w-full">+</div>
								<div class="text-lg text-center w-full">Create DB</div>
							</div>
						</button>
					</div>
				</div>
			</div>
		</div>

	</AuthenticatedLayout>
</template>

<script lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { defineComponent } from 'vue';


export default defineComponent({
	components: { AuthenticatedLayout, Link },
	tennants: {
		type: Object,
	},
	methods: {
		newdb() {
			const dbName = prompt("Please enter your name", "Harry Potter");
			axios.post(route('db.new', {}), { prompt: dbName });
		}
	}
});
</script>
