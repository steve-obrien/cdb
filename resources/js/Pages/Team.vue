<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
	auth: {
		type: Object
	},
	users: {
		type: Object,
	},
});
</script>

<template>
	<Head title="Dashboard" />

	<AuthenticatedLayout class="bg-gray-100">
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
		</template>

		<div class="p-4 md:p-8">
			<div class="sm:flex sm:items-center">
				<div class="sm:flex-auto">
					<h1 class="text-base font-semibold leading-6 text-gray-900">Users</h1>
					<p class="mt-2 text-sm text-gray-700">A list of all the users in your account including their name, title, email and role.</p>
				</div>
				<div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
					<button type="button" class="block rounded-full bg-black px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add user</button>
				</div>
			</div>

			<div class="mt-8 flow-root">
				<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
					<div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
						<div class="grid grid-cols-3 bg-white py-4 px-7">
							<div class="">Name</div>
							<div>Email</div>
						</div>
						<div v-for="user in users" class="bg-white py-4 px-7 grid grid-cols-3 my-2 rounded-sm">
							<div class="flex items-center">
								<img class="w-8 rounded-full mr-2" :src="user.avatar_url" />
								<span class="font-bold text-lg">{{user.name}}</span>
								<span v-if="auth.user.id == user.id" class="bg-gray-200 rounded-md px-2 ml-2">You</span>
							</div>
							<div class="flex">
								<span class="font-normal text-lg">{{user.email}}</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</AuthenticatedLayout>
</template>
